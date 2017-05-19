<?php

/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/13
 * Time: 17:57
 */
class UserService extends CI_Service
{
    public function getUser()
    {
        /** @var User_model $userModel */
        $userModel = $this->loadModel('User_model');
        return $userModel->getQueryBuilder()->select('title')->where([
            'title' => '1',
        ])->get()->result_array();
    }
}