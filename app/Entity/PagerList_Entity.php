<?php

namespace App\Entity;

class PagerList_Entity
{
    private $pager_no;
    private $title; //페이지 타이틀
    private $code;//코드명
    private $style;//스타일
    private $reader; //읽기 권한
    private $status; //페이지 상태
    private $writedate;

    /**
     * @return mixed
     */
    public function getPagerNo()
    {
        return $this->pager_no;
    }

    /**
     * @param mixed $pager_no
     */
    public function setPagerNo($pager_no): void
    {
        $this->pager_no = $pager_no;
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
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getWritedate()
    {
        return $this->writedate;
    }

    /**
     * @param mixed $writedate
     */
    public function setWritedate($writedate): void
    {
        $this->writedate = $writedate;
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
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * @param mixed $style
     */
    public function setStyle($style): void
    {
        $this->style = $style;
    }

    /**
     * @return mixed
     */
    public function getReader()
    {
        return $this->reader;
    }

    /**
     * @param mixed $reader
     */
    public function setReader($reader): void
    {
        $this->reader = $reader;
    }// 등록일


}