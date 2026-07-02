<?php

namespace App\Entity;

class Login_SessionEntity
{

    private $login_no;
    private $session_id;
    private $data;
    private $fid;
    private $status;

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
public function getLoginNo()
{
    return $this->login_no;
}/**
 * @param mixed $login_no
 */
public function setLoginNo($login_no): void
{
    $this->login_no = $login_no;
}/**
 * @return mixed
 */
public function getSessionId()
{
    return $this->session_id;
}/**
 * @param mixed $session_id
 */
public function setSessionId($session_id): void
{
    $this->session_id = $session_id;
}/**
 * @return mixed
 */
public function getData()
{
    return $this->data;
}/**
 * @param mixed $data
 */
public function setData($data): void
{
    $this->data = $data;
}/**
 * @return mixed
 */
public function getFid()
{
    return $this->fid;
}/**
 * @param mixed $fid
 */
public function setFid($fid): void
{
    $this->fid = $fid;
}

}