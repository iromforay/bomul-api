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
    // soft delete된 메모는 목록에서 제외.
    public function get_memo($parent_no, $parent_table)
    {
        $sql = "SELECT * FROM `$this->table` WHERE `parent_table`=".$this->db->escape($parent_table)." AND parent_no = ".$this->db->escape($parent_no)." AND `is_deleted` = 0 ORDER BY `$this->primaryKey` ASC";
        return $this->getExecQuery($sql);
    }

    public function update_memo(Memo_Entity $entity)
    {
        $osql = "UPDATE `$this->table` SET `memo_text`=%s WHERE `$this->primaryKey`=%s";
        $sql = sprintf($osql, $this->db->escape($entity->getMemoText()), $this->db->escape($entity->getMemoNo()));
        return $this->safeExecQuery($sql);
    }

    // Board::Postboard_del() 참고 - soft delete (is_deleted=1로만 세우고 row는 남김)
    public function delete_memo(Memo_Entity $entity)
    {
        $sql = "UPDATE `$this->table` SET `is_deleted`=1 WHERE `$this->primaryKey`=".$this->db->escape($entity->getMemoNo());
        return $this->safeExecQuery($sql);
    }

    // 부모(예: note)가 삭제될 때 딸린 메모를 일괄 soft delete (이미 삭제된 것도 포함해서 조건 없이 덮어씀 - 멱등)
    public function delete_by_parent($parent_table, $parent_no)
    {
        $sql = "UPDATE `$this->table` SET `is_deleted`=1 WHERE `parent_table`=".$this->db->escape($parent_table)." AND `parent_no`=".$this->db->escape($parent_no);
        return $this->safeExecQuery($sql);
    }
}
