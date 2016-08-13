<?php

/**
 * Created by PhpStorm.
 * User: nalux
 * Date: 2016/8/13
 * Time: 17:55
 */
class CI_Service
{
    /** @var CI_Controller  */
    protected $controller = null;

    /** @var CI_Loader  */
    protected $controllerLoad = null;
    
    protected function loadModel($name)
    {
        $this->controllerLoad->model($name);
        return $this->controller->$name;
    }
    
    public function setController(CI_Controller $controller)
    {
        $this->controller = $controller;
        $this->controllerLoad = $controller->load;
    }
}