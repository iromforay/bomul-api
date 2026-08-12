<?php

namespace App\Models;

use App\Entity\Img_Entity;

class ImgModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"img","img_no");
        return $this;
    }

    public function save_img(Img_Entity $entity)
    {
        $osql="INSERT INTO `$this->table` SET
                `parent_table`=%s, `parent_no`=%s,
                 `img_dir`=%s,  `img_name`=%s,  `write_date`=now()";
        $sql = sprintf($osql,  $this->db->escape( $entity->getParentTable()), $this->db->escape( $entity->getParentNo()),
            $this->db->escape( $entity->getImgDir()),$this->db->escape( $entity->getImgName())
        );
//     echo "\n".$sql;
        $img_no = $this->safeExecQuery($sql);
        return $img_no;
    }

    public function getLastId()
    {
        $sql="SELECT max(".$this->primaryKey.") as last_id FROM `$this->table`";

        $row =  $this->getExecQuery($sql);

        return $row[0]['last_id']?? 0;

    }

    // $parent_table 기본값은 menu로 유지 - 기존 호출부(메뉴)는 그대로 동작
    // write_date는 동시 업로드 시 초 단위로 값이 겹쳐 정렬이 불안정하므로, 등록순서를 정확히 반영하는
    // img_no(auto_increment) 오름차순으로 정렬 (오래된 순)
    public function get_image($parent_no, $parent_table = 'menu',$count=0)
    {
        $sql = "SELECT * FROM `$this->table` WHERE `parent_table`=".$this->db->escape($parent_table)." AND parent_no = ".$this->db->escape($parent_no)." ORDER BY `$this->primaryKey` ASC";
        if($count!=0){
            $sql.=" LIMIT $count";
        }
        return $this->getExecQuery($sql);
    }

    public function delete_image(Img_Entity $entity)
    {
        $sql="DELETE FROM `$this->table` WHERE `$this->primaryKey`=".$this->db->escape($entity->getImgNo());
        return $this->safeExecQuery($sql);
    }
    public function update_Img(Img_Entity $entity)
    {
        $osql="UPDATE `$this->table` SET
                `parent_table`=%s, `parent_no`=%s,
                 `img_dir`=%s, `img_name`=%s WHERE `$this->primaryKey`= %s";
        $sql = sprintf($osql,  $this->db->escape( $entity->getParentTable()), $this->db->escape( $entity->getParentNo()),
            $this->db->escape( $entity->getImgDir()),$this->db->escape( $entity->getImgName()), $this->db->escape($entity->getImgNo())
        );
     //     echo "\n".$sql;
        return $this->safeExecQuery($sql);
    }

}