<?php

namespace App\Models;

class SettingQueueModel extends CI_Model
{
    public function __construct($db)
    {
        parent::__construct($db, "setting_queue", "setting_no");
        return $this;
    }

    // Receiver가 push 수신 즉시 호출 - 원본 payload를 그대로 저장하고 PENDING으로 적재
    public function sql_save($queue_no, $fid, array $payload)
    {
        $sql = "INSERT INTO `$this->table` SET
                    `queue_no` = " . $this->db->escape((int) $queue_no) . ",
                    `fid`      = " . $this->db->escape($fid) . ",
                    `payload`  = " . $this->db->escape(json_encode($payload, JSON_UNESCAPED_UNICODE)) . ",
                    `status`   = 'PENDING',
                    `created_at` = NOW(),
                    `updated_at` = NOW()";
        return $this->safeExecQuery($sql);
    }

    // 콜백 cron이 처리할 대상 - 아직 처리 안 한(PENDING) 것들
    public function get_pending()
    {
        $sql = "SELECT * FROM `$this->table` WHERE `status` = 'PENDING' ORDER BY `setting_no` ASC LIMIT 20";
        return $this->getExecQuery($sql);
    }

    // 처리는 끝났는데(DONE/FAILED) 아직 콜백을 못 보낸 것들 - 실패한 콜백 재시도도 이 조건으로 자연히 다시 잡힘
    public function get_pending_callback()
    {
        $sql = "SELECT * FROM `$this->table`
                WHERE `status` IN ('DONE', 'FAILED') AND `callback_sent_at` IS NULL
                ORDER BY `setting_no` ASC LIMIT 20";
        return $this->getExecQuery($sql);
    }

    public function markResult($setting_no, string $status, ?string $message = null)
    {
        $sql = "UPDATE `$this->table` SET
                    `status` = " . $this->db->escape($status) . ",
                    `result_message` = " . ($message !== null ? $this->db->escape($message) : 'NULL') . ",
                    `updated_at` = NOW()
                WHERE `setting_no` = " . $this->db->escape((int) $setting_no);
        return $this->safeExecQuery($sql);
    }

    public function markCallbackSent($setting_no)
    {
        $sql = "UPDATE `$this->table` SET `callback_sent_at` = NOW() WHERE `setting_no` = " . $this->db->escape((int) $setting_no);
        return $this->safeExecQuery($sql);
    }
}
