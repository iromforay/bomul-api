<?php

namespace App\Entity;

class Build_Entity
{
    private $build_no;
    private $uses;
    private $code;
    private $build_title;
    private $type;
    private $img_url;
    private $file_name;
    private $text;
    private $bg_color;
    private $color;
    private $link_type;
    private $link;
    private $show_count;
    private $write_date;

    /**
     * @return mixed
     */
    public function getBuildNo()
    {
        return $this->build_no;
    }

    /**
     * @param mixed $build_no
     */
    public function setBuildNo($build_no): void
    {
        $this->build_no = $build_no;
    }

    /**
     * @return mixed
     */
    public function getUses()
    {
        return $this->uses;
    }

    /**
     * @param mixed $uses
     */
    public function setUses($uses): void
    {
        $this->uses = $uses;
    }


    /**
     * @return mixed
     */
    public function getBuildTitle()
    {
        return $this->build_title;
    }

    /**
     * @param mixed $build_title
     */
    public function setBuildTitle($build_title): void
    {
        $this->build_title = $build_title;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getImgUrl()
    {
        return $this->img_url;
    }

    /**
     * @param mixed $img_url
     */
    public function setImgUrl($img_url): void
    {
        $this->img_url = $img_url;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->file_name;
    }

    /**
     * @param mixed $file_name
     */
    public function setFileName($file_name): void
    {
        $this->file_name = $file_name;
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
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color): void
    {
        $this->color = $color;
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
    public function getLinkType()
    {
        return $this->link_type;
    }

    /**
     * @param mixed $link_type
     */
    public function setLinkType($link_type): void
    {
        $this->link_type = $link_type;
    }

    /**
     * @return mixed
     */
    public function getShowCount()
    {
        return $this->show_count;
    }

    /**
     * @param mixed $show_count
     */
    public function setShowCount($show_count): void
    {
        $this->show_count = $show_count;
    }


}
