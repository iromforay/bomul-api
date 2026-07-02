<?php

namespace App\Entity;

class Profile_Entity
{
    private $profile_no;
    private $nick_name;
    private $img_dir;
    private $img_name;

    /**
     * @return mixed
     */
    public function getProfileNo()
    {
        return $this->profile_no;
    }

    /**
     * @param mixed $profile_no
     */
    public function setProfileNo($profile_no): void
    {
        $this->profile_no = $profile_no;
    }

    /**
     * @return mixed
     */
    public function getNickName()
    {
        return $this->nick_name;
    }

    /**
     * @param mixed $nick_name
     */
    public function setNickName($nick_name): void
    {
        $this->nick_name = $nick_name;
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


}