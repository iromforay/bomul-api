<?php

namespace App\Entity;

class Memo_Entity
{
    private $memo_no;
    private $parent_table;
    private $parent_no;
    private $writer_no;
    private $writer_name;
    private $memo_text;
    private $write_date;

    /**
     * @return mixed
     */
    public function getMemoNo()
    {
        return $this->memo_no;
    }

    /**
     * @param mixed $memo_no
     */
    public function setMemoNo($memo_no): void
    {
        $this->memo_no = $memo_no;
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
    public function getWriterNo()
    {
        return $this->writer_no;
    }

    /**
     * @param mixed $writer_no
     */
    public function setWriterNo($writer_no): void
    {
        $this->writer_no = $writer_no;
    }

    /**
     * @return mixed
     */
    public function getWriterName()
    {
        return $this->writer_name;
    }

    /**
     * @param mixed $writer_name
     */
    public function setWriterName($writer_name): void
    {
        $this->writer_name = $writer_name;
    }

    /**
     * @return mixed
     */
    public function getMemoText()
    {
        return $this->memo_text;
    }

    /**
     * @param mixed $memo_text
     */
    public function setMemoText($memo_text): void
    {
        $this->memo_text = $memo_text;
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
