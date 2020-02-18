<?php 

class UsersController extends Zend_Controller_Action{

    public function indexAction(){}

    public function createAction(){

        // initialize form
        $form = new UserForm();
        $form->submit->setLabel("Create");

        $this->view->form = $form;

        if($this->_request->isPost()){
        
            $data = $this->_request->getPost();

            if($form->isValid($data)){

                $user = new Users();
                $row = $user->createRow();//create and empty row
                $row->name = $this->_request->getPost("name");
                $row->username = $this->_request->getPost("username");
                $row->password = AuthController::hash_password( $this->_request->getPost("password") );
                $row->save();//update the empty row data

                $this->_redirect("/auth/login");

            } else {
                $form->populate($data);
            }

        }

    }

}