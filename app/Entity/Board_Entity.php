<?php
namespace App\Entity;
use CodeIgniter\Entity\Entity;

class Board_Entity
{
    private $boards_no;
    private $bname;
    private $bcode;
    private $header_title;
    private $header_upload;
    private $header_dir;
    private $footer_title;
    private $footer_upload;
    private $footer_dir;
    private $style;
    private $writer;
    private $reader;
    private $lister;
    private $reply;
    private $secret;

    /**
     * @return mixed
     */
    public function getBoardsNo()
    {
        return $this->boards_no;
    }

    /**
     * @param mixed $boards_no
     */
    public function setBoardsNo($boards_no): void
    {
        $this->boards_no = $boards_no;
    }

    /**
     * @return mixed
     */
    public function getBname()
    {
        return $this->bname;
    }

    /**
     * @param mixed $bname
     */
    public function setBname($bname): void
    {
        $this->bname = $bname;
    }

    /**
     * @return mixed
     */
    public function getBcode()
    {
        return $this->bcode;
    }

    /**
     * @param mixed $bcode
     */
    public function setBcode($bcode): void
    {
        $this->bcode = $bcode;
    }

    /**
     * @return mixed
     */
    public function getHeaderTitle()
    {
        return $this->header_title;
    }

    /**
     * @param mixed $header_title
     */
    public function setHeaderTitle($header_title): void
    {
        $this->header_title = $header_title;
    }

    /**
     * @return mixed
     */
    public function getHeaderUpload()
    {
        return $this->header_upload;
    }

    /**
     * @param mixed $header_upload
     */
    public function setHeaderUpload($header_upload): void
    {
        $this->header_upload = $header_upload;
    }

    /**
     * @return mixed
     */
    public function getFooterTitle()
    {
        return $this->footer_title;
    }

    /**
     * @param mixed $footer_title
     */
    public function setFooterTitle($footer_title): void
    {
        $this->footer_title = $footer_title;
    }

    /**
     * @return mixed
     */
    public function getFooterUpload()
    {
        return $this->footer_upload;
    }

    /**
     * @param mixed $footer_upload
     */
    public function setFooterUpload($footer_upload): void
    {
        $this->footer_upload = $footer_upload;
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
    public function getWriter()
    {
        return $this->writer;
    }

    /**
     * @param mixed $writer
     */
    public function setWriter($writer): void
    {
        $this->writer = $writer;
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
    }

    /**
     * @return mixed
     */
    public function getLister()
    {
        return $this->lister;
    }

    /**
     * @param mixed $lister
     */
    public function setLister($lister): void
    {
        $this->lister = $lister;
    }

    /**
     * @return mixed
     */
    public function getReply()
    {
        return $this->reply;
    }

    /**
     * @param mixed $reply
     */
    public function setReply($reply): void
    {
        $this->reply = $reply;
    }

    /**
     * @return mixed
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @param mixed $secret
     */
    public function setSecret($secret): void
    {
        $this->secret = $secret;
    }

    /**
     * @return mixed
     */
    public function getHeaderDir()
    {
        return $this->header_dir;
    }

    /**
     * @param mixed $header_dir
     */
    public function setHeaderDir($header_dir): void
    {
        $this->header_dir = $header_dir;
    }

    /**
     * @return mixed
     */
    public function getFooterDir()
    {
        return $this->footer_dir;
    }

    /**
     * @param mixed $footer_dir
     */
    public function setFooterDir($footer_dir): void
    {
        $this->footer_dir = $footer_dir;
    }


}