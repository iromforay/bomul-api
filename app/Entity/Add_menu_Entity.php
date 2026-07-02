<?php

namespace App\Entity;

class Add_menu_Entity
{
    private $add_menu_no;
    private $code;
    private $key;
    private $text;
    private $show;
    private $img_dir;
    private $img_name;
    private $write_date;

    /**
     * @return mixed
     */
    public function getAddMenuNo()
    {
        return $this->add_menu_no;
    }

    /**
     * @param mixed $add_menu_no
     */
    public function setAddMenuNo($add_menu_no): void
    {
        $this->add_menu_no = $add_menu_no;
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
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     */
    public function setKey($key): void
    {
        $this->key = $key;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text): void
    {
        $this->text = $text;
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