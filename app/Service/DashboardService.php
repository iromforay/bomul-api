<?php

namespace App\Service;

/*
 * BO 대시보드(html/bo_dashboard.html 시안 대응) 데이터 조합.
 * site_analytics_daily_stats / property_analytics_daily는 새벽 배치(app/batch/analy.php →
 * Cron\Analytics)가 미리 채워둔 집계 테이블이라, 여기선 원본 이벤트 테이블을 조회하지 않고
 * 이 두 테이블만 읽는다 - 그래야 데이터가 아무리 쌓여도 대시보드 응답이 느려지지 않는다.
 *
 * 문의수(inquiry_count)는 고객노트 시스템과의 연동이 아직 없어서 여기서 채울 수 없다 -
 * top_properties의 inquiry_count는 항상 null, inquiry_data_available=false로 내려서
 * FE가 "연동 예정" 표시를 하도록 명시적으로 알려준다 (가짜 값을 만들어 보내지 않음).
 */
class DashboardService extends CI_Service
{
    private $log;
    private $dailyStatsModel;
    private $propertyDailyModel;
    private $noteService;

    public function __construct($log = "", $session_id = "")
    {
        parent::__construct($session_id);
        $this->log = $log;
        $this->dailyStatsModel = model('App\Models\SiteAnalyticsDailyStatsModel', true, $this->db);
        $this->dailyStatsModel->getLog($log);
        $this->propertyDailyModel = model('App\Models\PropertyAnalyticsDailyModel', true, $this->db);
        $this->propertyDailyModel->getLog($log);
        $this->noteService = new NoteService($log, $session_id);
        return $this;
    }

    // $day: 숨은 기능(현재 FE 미사용, html 시안에도 미노출) - 넘기면 그 하루만, 전일 대비로 조회.
    public function getSummary($year, $month, $day = null)
    {
        if ($day) {
            $thisStart = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $thisEnd = $thisStart;

            $prevTs = strtotime('-1 day', strtotime($thisStart));
            $prevStart = $prevEnd = date('Y-m-d', $prevTs);
        } else {
            $thisStart = sprintf('%04d-%02d-01', $year, $month);
            $thisEnd = date('Y-m-t', strtotime($thisStart));

            $prevTs = strtotime('-1 month', strtotime($thisStart));
            $prevStart = date('Y-m-01', $prevTs);
            $prevEnd = date('Y-m-t', $prevTs);
        }

        $thisTotals = $this->dailyStatsModel->getTotals($thisStart, $thisEnd);
        $prevTotals = $this->dailyStatsModel->getTotals($prevStart, $prevEnd);

        // 추이차트는 선택한 월과 무관하게 항상 "오늘 기준 최근 30일" 고정 (시안과 동일)
        $trendEnd = date('Y-m-d');
        $trendStart = date('Y-m-d', strtotime('-29 days'));
        $trendRows = $this->dailyStatsModel->getDailyRange($trendStart, $trendEnd);

        $topProperties = $this->propertyDailyModel->getTopByViews($thisStart, $thisEnd, 5);
        foreach ($topProperties as &$p) {
            $p['address'] = trim(($p['addr_sido'] ?? '') . ' ' . ($p['addr_sigungu'] ?? '') . ' ' . ($p['addr_dong'] ?? ''));
            $p['view_count'] = (int)$p['view_count'];
            $p['avg_dwell_ms'] = isset($p['avg_dwell_ms']) ? (int)$p['avg_dwell_ms'] : null;
            $p['inquiry_count'] = null; // 고객노트 연동 전까지 알 수 없음 - 가짜 값 안 채움
            unset($p['addr_sido'], $p['addr_sigungu'], $p['addr_dong']);
        }
        unset($p);

        // 이번달 등록된 상담노트의 진행상태 분포(상담중/매물제안/계약완료/이탈) - Admin/note/list 상단 탭과 동일 집계, 이번달로 스코프.
        $noteStatusBreakdown = $this->noteService->status_total_count([
            "`created_at` >= '{$thisStart} 00:00:00'",
            "`created_at` <= '{$thisEnd} 23:59:59'",
        ]);

        // 기간 선택기(period 드롭다운)용 - 실 데이터가 있는 월만, 1년 전을 마지노선으로 제외.
        $oneYearAgo = date('Y-m-d', strtotime('-1 year'));
        $availablePeriods = array_map(function ($ym) {
            $y = (int)substr($ym, 0, 4);
            $m = (int)substr($ym, 4, 2);
            return ['period' => $ym, 'year' => $y, 'month' => $m, 'label' => "{$y}년 {$m}월"];
        }, $this->dailyStatsModel->getAvailableMonths($oneYearAgo));

        return [
            'period' => ['year' => (int)$year, 'month' => (int)$month, 'day' => $day ? (int)$day : null, 'start' => $thisStart, 'end' => $thisEnd],
            'kpi' => [
                'naver_referral' => [
                    'current' => (int)($thisTotals['naver_referral_count'] ?? 0),
                    'previous' => (int)($prevTotals['naver_referral_count'] ?? 0),
                ],
                'visitor_count' => [
                    'current' => (int)($thisTotals['visitor_count'] ?? 0),
                    'previous' => (int)($prevTotals['visitor_count'] ?? 0),
                ],
                'phone_click' => [
                    'current' => (int)($thisTotals['phone_click_count'] ?? 0),
                    'previous' => (int)($prevTotals['phone_click_count'] ?? 0),
                ],
                'new_visitor_count' => (int)($thisTotals['new_visitor_count'] ?? 0),
                'returning_visitor_count' => (int)($thisTotals['returning_visitor_count'] ?? 0),
            ],
            'referral_breakdown' => [
                'naver' => (int)($thisTotals['naver_referral_count'] ?? 0),
                'direct' => (int)($thisTotals['direct_referral_count'] ?? 0),
                'other' => (int)($thisTotals['other_referral_count'] ?? 0),
            ],
            'visitor_trend' => array_map(function ($r) {
                return ['date' => $r['stat_date'], 'count' => (int)$r['visitor_count']];
            }, $trendRows),
            'top_properties' => $topProperties,
            // 문의수 연동 전이라는 걸 FE가 명시적으로 알고 "연동 예정" 등으로 표시하도록 하는 플래그.
            'inquiry_data_available' => false,
            // 이번달 등록된 상담노트 진행상태 분포 (Admin/note/list 상단 탭 counts와 동일 형태)
            'note_status_breakdown' => $noteStatusBreakdown,
            // 기간 선택기용 - 실 데이터가 있는 월만 최신순, 1년 전까지만
            'available_periods' => $availablePeriods,
        ];
    }
}
