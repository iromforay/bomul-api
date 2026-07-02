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

    public function get_image($menu_no)
    {
        $sql = "SELECT * FROM `$this->table` WHERE `parent_table`='menu' AND parent_no = ".$this->db->escape($menu_no);
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