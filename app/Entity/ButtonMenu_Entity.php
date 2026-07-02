<?php

namespace App\Entity;

class ButtonMenu_Entity
{
    private $menu_no;
    private $menu_name;
    private $img_name;
    private $img_dir;
    private $bg_color;
    private $show;
    private $link;
    private $write_date;

    /**
     * @return mixed
     */
    public function getMenuNo()
    {
        return $this->menu_no;
    }

    /**
     * @param mixed $menu_no
     */
    public function setMenuNo($menu_no): void
    {
        $this->menu_no = $menu_no;
    }

    /**
     * @return mixed
     */
    public function getMenuName()
    {
        return $this->menu_name;
    }

    /**
     * @param mixed $menu_name
     */
    public function setMenuName($menu_name): void
    {
        $this->menu_name = $menu_name;
    }

    /**
     * @return mixed
     */
    public function getImgName()
    {
        return $this->img_name;
    }

    /**
     * @param mixed $img_name
     */
    public function setImgName($img_name): void
    {
        $this->img_name = $img_name;
    }

    /**
     * @return mixed
     */
    public function getImgDir()
    {
        return $this->img_dir;
    }

    /**
     * @param mixed $img_dir
     */
    public function setImgDir($img_dir): void
    {
        $this->img_dir = $img_dir;
    }

    /**
     * @return mixed
     */
    public function getBgColor()
    {
        return $this->bg_color;
    }

    /**
     * @param mixed $bg_color
     */
    public function setBgColor($bg_color): void
    {
        $this->bg_color = $bg_color;
    }

    /**
     * @return mixed
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * @param mixed $show
     */
    public function setShow($show): void
    {
        $this->show = $show;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link): void
    {
        $this->link = $link;
    }

    /**
     * @return mixed
     */
    public function getWriteDate()
    {
        return $this->write_date;
    }

    /**
     * @param mixed $write_date
     */
    public function setWriteDate($write_date): void
    {
        $this->write_date = $write_date;
    }


}
