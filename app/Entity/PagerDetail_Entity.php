<?php

namespace App\Entity;

class PagerDetail_Entity
{
    private $pager_dno;
    private $pager_no;
    private $body;
    private $sorty;
    private $status = "T";
    private $write_date;

    /**
     * @return mixed
     */
    public function getPagerDno()
    {
        return $this->pager_dno;
    }

    /**
     * @param mixed $pager_dno
     */
    public function setPagerDno($pager_dno): void
    {
        $this->pager_dno = $pager_dno;
    }

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
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getSorty()
    {
        return $this->sorty;
    }

    /**
     * @param mixed $sorty
     */
    public function setSorty($sorty): void
    {
        $this->sorty = $sorty;
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