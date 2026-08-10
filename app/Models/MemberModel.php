<?php

namespace App\Models;

// 중앙 bomul DB의 member 테이블 - 중개사가 자기 상점(bo.{fid}...) 로그인할 때 쓰는 계정.
// LoginModel::adminLogin()이 이 테이블을 user_id로 조회해서 인증한다.
class MemberModel extends CI_Model
{
    public function __construct($db)
    {
        parent::__construct($db, "member", "member_no");
        return $this;
    }

    public function existsByFid($fid): bool
    {
        $sql = "SELECT `member_no` FROM `$this->table` WHERE `fid` = " . $this->db->escape($fid) . " LIMIT 1";
        $rows = $this->getExecQuery($sql);
        return !empty($rows);
    }

    public function existsByUserId($user_id): bool
    {
        $sql = "SELECT `member_no` FROM `$this->table` WHERE `user_id` = " . $this->db->escape($user_id) . " LIMIT 1";
        $rows = $this->getExecQuery($sql);
        return !empty($rows);
    }

    // id/pwd/fid 3개만 등록한다 - user_pwd는 customer.customer_pw와 동일 SHA-256 해시라
    // 그대로 복사(재해시 아님). service 등 나머지 컬럼은 여기서 안 건드림 - 서비스 오픈/마감
    // 같은 별도 흐름에서 관리될 값이라 이 프로비저닝 단계의 책임이 아니다.
    public function sql_save(array $data)
    {
        $sql = "INSERT INTO `$this->table` SET
                    `user_id`  = " . $this->db->escape($data['user_id']) . ",
                    `user_pwd` = " . $this->db->escape($data['user_pwd']) . ",
                    `fid`      = " . $this->db->escape($data['fid']);

        return $this->safeExecQuery($sql);
    }

    public function updateServiceStatus($fid, string $status)
    {
        $sql = "UPDATE `$this->table` SET `service` = " . $this->db->escape($status) . " WHERE `fid` = " . $this->db->escape($fid);
        return $this->safeExecQuery($sql);
    }

    // 사용자웹 BaseController::check_service_status()가 호출 - member row가 없으면 null
    // (기존 레거시 상점 등 - 이 경우 호출부에서 운영중으로 간주한다)
    public function getServiceByFid($fid): ?string
    {
        $sql = "SELECT `service` FROM `$this->table` WHERE `fid` = " . $this->db->escape($fid) . " LIMIT 1";
        $rows = $this->getExecQuery($sql);
        return $rows[0]['service'] ?? null;
    }
}
