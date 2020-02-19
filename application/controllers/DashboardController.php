<?php

class DashboardController extends Zend_Controller_Action
{
    public function indexAction()
    {

        //if user is not logged in, redirect to login page
        if (!Zend_Auth::getInstance()->hasIdentity()) {
            $this->_redirect("/auth/login");
        }

        $session = Zend_Auth::getInstance()->getStorage();

        $this->view->title = "Dashboard";
    }
}
