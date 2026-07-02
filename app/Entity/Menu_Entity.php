<?php

namespace App\Entity;

class Menu_Entity
{
    private $menu_no;
    private $name;
    private $code;
    private $bgcolor;
    private $transparent;
    private $title;
    private $title_show;
    private $bgcolor_show;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getBgcolor()
    {
        return $this->bgcolor;
    }

    /**
     * @param mixed $bgcolor
     */
    public function setBgcolor($bgcolor): void
    {
        $this->bgcolor = $bgcolor;
    }

    /**
     * @return mixed
     */
    public function getTransparent()
    {
        return $this->transparent;
    }

    /**
     * @param mixed $transparent
     */
    public function setTransparent($transparent): void
    {
        $this->transparent = $transparent;
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitleShow()
    {
        return $this->title_show;
    }

    /**
     * @param mixed $title_show
     */
    public function setTitleShow($title_show): void
    {
        $this->title_show = $title_show;
    }

    /**
     * @return mixed
     */
    public function getBgcolorShow()
    {
        return $this->bgcolor_show;
    }

    /**
     * @param mixed $bgcolor_show
     */
    public function setBgcolorShow($bgcolor_show): void
    {
        $this->bgcolor_show = $bgcolor_show;
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