<?php

class IndexController extends Zend_Controller_Action{

    public function indexAction(){
        
    }

    public function dashboardAction(){
        
        if(!Zend_Auth::getInstance()->hasIdentity()) $this->_redirect("/auth/login");
    }

}