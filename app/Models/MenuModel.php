<?php

namespace App\Models;

use App\Entity\ButtonMenu_Entity;
use App\Entity\Img_Entity;
use App\Entity\Menu_Entity;

class MenuModel extends CI_Model
{
    public function __construct($dbs)
    {
        parent::__construct($dbs,"menu","menu_no");
        return $this;
    }
    public function sql_save(Menu_Entity $entity)
    {


        $osql = " INSERT INTO `$this->table` SET
                        `name`=%s,`link`=%s, `img`=%s,
                        `transparent`=%s,`img_over`=%s,`sorty`=%s,`write_date`=now()
                ";
        $sql = sprintf($osql,  $this->db->escape( $entity->getName()), $this->db->escape( $entity->getLink()), $this->db->escape( $entity->getImg()),
            $this->db->escape($entity->getTransparent()),$this->db->escape( $entity->getImgOver()), $this->db->escape( $entity->getSorty())
        );

        $boards_no =  $this->safeExecQuery($sql);
        return $boards_no;
    }

    public function sql_edit(Menu_Entity $entity)
    {
        $set = array();
        $sql  = "UPDATE `$this->table` SET ";

        $entity->getName()!=""           ?    $set[] = "`name`=".           $this->db->escape($entity->getName())       : "";
        $entity->getCode()!=""           ?    $set[] = "`code`=".           $this->db->escape($entity->getCode())       : "";
        $entity->getBgcolor()!=""        ?    $set[] = "`bgcolor`=".        $this->db->escape($entity->getBgcolor())    : "";
        $entity->getTransparent()!=""    ?    $set[] = "`transparent`=".    $this->db->escape($entity->getTransparent()): "";
        $entity->getTitle()!=""          ?    $set[] = "`title`=".          $this->db->escape($entity->getTitle())      : "";
        $entity->getTitleShow()!=""      ?    $set[] = "`title_show`=".     $this->db->escape($entity->getTitleShow())  : "";
        $entity->getBgcolorShow()!=""    ?    $set[] = "`bgcolor_show`=".   $this->db->escape($entity->getBgcolorShow()): "";

        $sql .= implode(",", $set);

        $sql .= " WHERE `$this->primaryKey` = ". $this->db->escape($entity->getMenuNo());

        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }

    public function findbyCode($code)
    {
        $sql="SELECT * FROM `menu` WHERE `code`=".$this->db->escape($code);
        $row = $this->getExecQuery($sql);

        if(isset($row[0]))
            $data['row'] = $row[0];
        else $data['row']=null;
        return $data;
    }

    public function get_image($menu_no)
    {
        $sql = "SELECT * FROM `img` WHERE `parent_table`='menu' AND parent_no = ".$this->db->escape($menu_no);
        return $this->getExecQuery($sql);
    }

    public function get_button_menu()
    {
        $sql="SELECT * FROM `button_menu`";
        return $this->getExecQuery($sql);
    }

    public function findbuttonmenubyid($button_menu_no)
    {
        $sql="SELECT * FROM `button_menu` WHERE `button_menu_no` =".$button_menu_no;
        $row = $this->getExecQuery($sql);
        return $row[0];
    }
    public function edit_button_menu(ButtonMenu_Entity $entity)
    {
        $osql="UPDATE button_menu SET `menu_name`=%s, `img_name`=%s, `img_dir`=%s, `bg_color`=%s, `show`=%s , `link`=%s WHERE button_menu_no = %s";
        $sql = sprintf($osql,  $this->db->escape( $entity->getMenuName()), $this->db->escape( $entity->getImgName()), $this->db->escape( $entity->getImgDir()),
            $this->db->escape( $entity->getBgColor()), $this->db->escape( $entity->getShow()), $this->db->escape( $entity->getLink())
            , $this->db->escape( $entity->getMenuNo())
        );
   // echo $sql;
        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }

    public function edit_menu_show_by_code($show="Y",$code)
    {
        $sql  = "UPDATE `$this->table` SET `menu_show`=".$this->db->escape($show)." WHERE `code`=".$this->db->escape($code);

        if($this->safeExecQuery($sql))
        {
            return true;
        }else{
            return false;
        }
    }

    public function save_button_menu(ButtonMenu_Entity $entity)
    {
        $osql="INSERT INTO button_menu SET
                `menu_name`=%s, `img_name`=%s, `img_dir`=%s, 
                            `bg_color`=%s, `show`=%s , `link`=%s , `write_date`=now()";
        $sql = sprintf($osql,  $this->db->escape( $entity->getMenuName()), $this->db->escape( $entity->getImgName()), $this->db->escape( $entity->getImgDir()),
            $this->db->escape( $entity->getBgColor()), $this->db->escape( $entity->getShow()), $this->db->escape( $entity->getLink())
            );
        echo $sql;
        $menu_no = $this->safeExecQuery($sql);
        return $menu_no;

    }


}



/*
 * menu
 *
 * menu_no int
 * name varchar(64) -- 메뉴명
 * link varchar(64) -- 링크
 * img varchar(64)  -- 메뉴 이미지
 * img_over varchar(64) --메뉴 마우스 오버 이미지
 * sorty int -- 순서
 *
 * */