<?php

namespace App\Entity;

class FunctionMenu_Entity
{
private $function_menu_no;
private $function_menu_key; // 메뉴 코드
private $function_menu_name; //메뉴 코드 네임
private $function_menu_value; // 메뉴 코드 네임 또는 값

    /**
     * @return mixed
     */
    public function getFunctionMenuNo()
    {
        return $this->function_menu_no;
    }

    /**
     * @param mixed $function_menu_no
     */
    public function setFunctionMenuNo($function_menu_no): void
    {
        $this->function_menu_no = $function_menu_no;
    }

    /**
     * @return mixed
     */
    public function getFunctionMenuKey()
    {
        return $this->function_menu_key;
    }

    /**
     * @param mixed $function_menu_key
     */
    public function setFunctionMenuKey($function_menu_key): void
    {
        $this->function_menu_key = $function_menu_key;
    }

    /**
     * @return mixed
     */
    public function getFunctionMenuName()
    {
        return $this->function_menu_name;
    }

    /**
     * @param mixed $function_menu_name
     */
    public function setFunctionMenuName($function_menu_name): void
    {
        $this->function_menu_name = $function_menu_name;
    }

    /**
     * @return mixed
     */
    public function getFunctionMenuValue()
    {
        return $this->function_menu_value;
    }

    /**
     * @param mixed $function_menu_value
     */
    public function setFunctionMenuValue($function_menu_value): void
    {
        $this->function_menu_value = $function_menu_value;
    } //메뉴 코드 값


}