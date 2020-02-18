<?php 

class UsersController extends Zend_Controller_Action{

    public function indexAction(){}

    public function createAction(){

        $form = new UserForm();
        $form->submit->setLabel("Create");

        $this->view->form = $form;

        if($this->_request->isPost()){
        
            // $user = new User
            $data = $this->_request->getPost();

            if($form->isValid($data)){

                $user = new Users();
                $row = $user->createRow();
                $row->name = $this->_request->getPost("name");
                $row->username = $this->_request->getPost("username");
                $row->password = AuthController::hash_password( $this->_request->getPost("password") );
                $row->save();

                $this->_redirect("/auth/login");

            } else {
                $form->populate($data);
            }

        }

    }

}