<?php

namespace App\Entity;

class Post_Entity
{
    private $post_no;
    private $bcode;
    private $user_id;
    private $name;
    private $password;
    private $secret="F";
    private $tel;
    private $status;
    private $subject;
    private $content;
    private $file_name;
    private $orgin_name;
    private $file_dir;
    private $write_date;
    private $modify_date;
    private $view_count;
    private $add01;
    private $add02;

    /**
     * @return mixed
     */
    public function getPostNo()
    {
        return $this->post_no;
    }

    /**
     * @param mixed $post_no
     */
    public function setPostNo($post_no): void
    {
        $this->post_no = $post_no;
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
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
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
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param mixed $tel
     */
    public function setTel($tel): void
    {
        $this->tel = $tel;
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
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): void
    {
        $this->content = $content;
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
    public function getFileDir()
    {
        return $this->file_dir;
    }

    /**
     * @param mixed $file_dir
     */
    public function setFileDir($file_dir): void
    {
        $this->file_dir = $file_dir;
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
    public function getModifyDate()
    {
        return $this->modify_date;
    }

    /**
     * @param mixed $modify_date
     */
    public function setModifyDate($modify_date): void
    {
        $this->modify_date = $modify_date;
    }

    /**
     * @return mixed
     */
    public function getViewCount()
    {
        return $this->view_count;
    }

    /**
     * @param mixed $view_count
     */
    public function setViewCount($view_count): void
    {
        $this->view_count = $view_count;
    }


    /**
     * @return mixed
     */
    public function getAdd01()
    {
        return $this->add01;
    }

    /**
     * @param mixed $add01
     */
    public function setAdd01($add01): void
    {
        $this->add01 = $add01;
    }

    /**
     * @return mixed
     */
    public function getAdd02()
    {
        return $this->add02;
    }

    /**
     * @param mixed $add02
     */
    public function setAdd02($add02): void
    {
        $this->add02 = $add02;
    }

    /**
     * @return mixed
     */
    public function getOrginName()
    {
        return $this->orgin_name;
    }

    /**
     * @param mixed $orgin_name
     */
    public function setOrginName($orgin_name): void
    {
        $this->orgin_name = $orgin_name;
    }



}
