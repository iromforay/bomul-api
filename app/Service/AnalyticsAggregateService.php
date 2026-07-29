<?php

namespace App\Service;

use App\Entity\SiteAnalyticsDailyStats_Entity;

/*
 * 방문자 트래킹 - 일별 집계 배치 서비스.
 *
 * app/Controllers/Cron/Analytics.php에서 호출된다 (curl로 /cron/analytics/daily-aggregate를
 * 호출하면 그 요청의 Host 헤더 서브도메인이 SHOP_CODE가 되고, 그 중개사 DB에 대해서만 집계한다
 * - CI_Service 생성자가 setDatabase(SHOP_CODE)로 이미 전환해둔 커넥션을 그대로 쓴다).
 *
 * 원본(site_analytics_visit/event)을 매번 실시간으로 GROUP BY 하지 않고, 하루 1행/매물 1행으로
 * 미리 집계해서 site_analytics_daily_stats / property_analytics_daily에 적재해두는 이유:
 * 원본은 계속 쌓이는 시계열 테이블이라 시간이 지날수록 실시간 집계 쿼리의 스캔 범위가 계속
 * 늘어난다 - 미리 집계해두면 대시보드 조회 비용이 원본 누적량과 무관하게 "조회하는 날짜 수"에만
 * 비례한다.
 *
 * 같은 날짜로 재실행해도 안전(멱등) - INSERT ... ON DUPLICATE KEY UPDATE 사용, 배치 실패 후
 * 수동 재실행 시 중복 없이 그 날짜 값만 덮어쓴다.
 */
class AnalyticsAggregateService extends CI_Service
{
    private $visitModel;
    private $eventModel;
    private $dailyStatsModel;
    private $propertyDailyModel;
    private $log;

    public function __construct($log = "", $session_id = "")
    {
        $this->log = $log;
        parent::__construct($session_id);
        $this->visitModel = model('App\Models\SiteAnalyticsVisitModel', true, $this->db);
        $this->eventModel = model('App\Models\SiteAnalyticsEventModel', true, $this->db);
        $this->dailyStatsModel = model('App\Models\SiteAnalyticsDailyStatsModel', true, $this->db);
        $this->dailyStatsModel->getLog($log);
        $this->propertyDailyModel = model('App\Models\PropertyAnalyticsDailyModel', true, $this->db);
        $this->propertyDailyModel->getLog($log);
        return $this;
    }

    // $date 생략 시 "어제" 기준 (새벽 배치는 항상 전날 하루치를 집계한다 - 당일은 아직 안 끝난 데이터라 제외)
    public function aggregateDaily($date = null)
    {
        $date = $date ?? date('Y-m-d', strtotime('-1 day'));

        $visitorStats = $this->visitModel->getDailyVisitorStats($date);
        $eventStats = $this->eventModel->getDailyEventStats($date);

        $visitorCount = (int)($visitorStats['visitor_count'] ?? 0);
        $naverCount = (int)($visitorStats['naver_referral_count'] ?? 0);
        $directCount = (int)($visitorStats['direct_referral_count'] ?? 0);
        $mobileCount = (int)($visitorStats['mobile_count'] ?? 0);

        $entity = new SiteAnalyticsDailyStats_Entity();
        $entity->fill([
            'stat_date' => $date,
            'visitor_count' => $visitorCount,
            'pageview_count' => (int)($eventStats['pageview_count'] ?? 0),
            'new_visitor_count' => (int)($visitorStats['new_visitor_count'] ?? 0),
            'returning_visitor_count' => (int)($visitorStats['returning_visitor_count'] ?? 0),
            'naver_referral_count' => $naverCount,
            'direct_referral_count' => $directCount,
            'other_referral_count' => max(0, $visitorCount - $naverCount - $directCount),
            'pc_count' => max(0, $visitorCount - $mobileCount),
            'mobile_count' => $mobileCount,
            'phone_click_count' => (int)($eventStats['phone_click_count'] ?? 0),
        ]);
        $siteOk = $this->dailyStatsModel->upsert($entity);

        $propertyOk = $this->propertyDailyModel->rebuildForDate($date);

        if (isset($this->log)) {
            $this->log->info("[REV] 일별 집계 완료 date=".$date." site=".var_export($siteOk, true)." property=".var_export($propertyOk, true));
        }

        return [
            'date' => $date,
            'site_ok' => $siteOk,
            'property_ok' => $propertyOk,
        ];
    }
}
