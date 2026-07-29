<?php

namespace App\Controllers\Cron;

use App\Libraries\AT_Result;
use App\Models\HelpModel;
use CodeIgniter\Controller;

/*
 * 새벽 배치(app/batch/analy.php)가 순회 대상 중개사 목록을 가져오는 엔드포인트.
 * api.bomul.co.kr 호스트로 들어오므로(app/Config/Routes.php) 오토라우팅으로 잡힌다 -
 * 별도 라우트 등록 불필요.
 *
 * 특정 중개사 DB가 아니라 중앙(bomul) DB만 보면 되는 요청이라 SHOP_CODE/setDatabase() 전환이
 * 필요 없다 - db_connect('default')로 붙은 커넥션 그대로 HelpModel에 넘긴다
 * (HelpModel의 쿼리들은 항상 bomul.테이블로 풀네임 지정하므로 현재 선택된 DB와 무관하게 동작).
 */
class Member extends Controller
{
    public function getActivefids()
    {
        if (!$this->checkToken()) {
            echo AT_Result::toJson(AT_Result::toFailData('unauthorized'));
            return;
        }

        $db = db_connect('default');
        $helpModel = model(HelpModel::class, true, $db);

        $fids = $helpModel->get_active_fids();

        echo AT_Result::toJson(AT_Result::toSuccessData("", ["fids" => $fids]));
    }

    private function checkToken()
    {
        $secret = env('CRON_SECRET', '');
        if ($secret === '' || $secret === null) {
            return false;
        }
        return hash_equals($secret, (string)$this->request->getVar('token'));
    }
}
