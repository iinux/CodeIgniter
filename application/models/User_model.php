<?php

/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/13
 * Time: 22:18
 */
class User_model extends CI_Model
{
    public $tableName = 'article';
    public function get()
    {
        return $this->db->get($this->tableName)->result_array();
    }
}