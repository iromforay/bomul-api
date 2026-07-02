<?php

namespace App\Entity;

class Img_Entity
{
    private $img_no;
    private $parent_table;
    private $parent_no;
    private $img_name;
    private $img_dir;
    private $write_date;

    /**
     * @return mixed
     */
    public function getImgNo()
    {
        return $this->img_no;
    }

    /**
     * @param mixed $img_no
     */
    public function setImgNo($img_no): void
    {
        $this->img_no = $img_no;
    }

    /**
     * @return mixed
     */
    public function getParentTable()
    {
        return $this->parent_table;
    }

    /**
     * @param mixed $parent_table
     */
    public function setParentTable($parent_table): void
    {
        $this->parent_table = $parent_table;
    }

    /**
     * @return mixed
     */
    public function getParentNo()
    {
        return $this->parent_no;
    }

    /**
     * @param mixed $parent_no
     */
    public function setParentNo($parent_no): void
    {
        $this->parent_no = $parent_no;
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



