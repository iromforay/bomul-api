<?php

namespace App\Models;

use App\Entity\Memo_Entity;

class MemoModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"memo","memo_no");
        return $this;
    }

    public function save_memo(Memo_Entity $entity)
    {
        $osql = "INSERT INTO `$this->table` SET
                `parent_table`=%s, `parent_no`=%s,
                `writer_no`=%s, `writer_name`=%s, `memo_text`=%s, `write_date`=now()";
        $sql = sprintf($osql, $this->db->escape($entity->getParentTable()), $this->db->escape($entity->getParentNo()),
            $this->db->escape($entity->getWriterNo()), $this->db->escape($entity->getWriterName()), $this->db->escape($entity->getMemoText())
        );
        $memo_no = $this->safeExecQuery($sql);
        return $memo_no;
    }

    // write_date는 동시 작성 시 초 단위로 값이 겹칠 수 있어, 등록순서를 정확히 반영하는
    // memo_no(auto_increment) 오름차순으로 정렬 (오래된 순)
    public function get_memo($parent_no, $parent_table)
    {
        $sql = "SELECT * FROM `$this->table` WHERE `parent_table`=".$this->db->escape($parent_table)." AND parent_no = ".$this->db->escape($parent_no)." ORDER BY `$this->primaryKey` ASC";
        return $this->getExecQuery($sql);
    }

    public function update_memo(Memo_Entity $entity)
    {
        $osql = "UPDATE `$this->table` SET `memo_text`=%s WHERE `$this->primaryKey`=%s";
        $sql = sprintf($osql, $this->db->escape($entity->getMemoText()), $this->db->escape($entity->getMemoNo()));
        return $this->safeExecQuery($sql);
    }

    public function delete_memo(Memo_Entity $entity)
    {
        $sql = "DELETE FROM `$this->table` WHERE `$this->primaryKey`=".$this->db->escape($entity->getMemoNo());
        return $this->safeExecQuery($sql);
    }
}
