<?php

class AuthController extends Zend_Controller_Action{

    public static function hash_password($password, $salt = null){

        if($salt === null) $salt = "/�#�d�n�J�{�:aO";

        return password_hash($password, PASSWORD_BCRYPT, ["salt" => $salt]);

    }

    //this function is to be called by zend callback adapter, with provides password and hash
    public static function check_password($password, $hash){

        return password_verify($password, $hash);

    }

    public function loginAction(){

        $form = new LoginForm();
        $form->submit->setLabel("Login");
        $this->view->form = $form;
        
        if($this->_request->isPost()){
            global $config;
            $auth = Zend_Auth::getInstance();

            $formData = $this->_request->getPost();
            if($form->isValid($formData)){

                $username = $this->_request->getPost("username");
                $password = $this->_request->getPost("password");

                $adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(), $config->db->params->table->users, "username", "password");
                $adapter->setIdentity($username)
                ->setCredential(AuthController::hash_password($password));
                
                $session = new Zend_Auth_Storage_Session("identity");

                //Create a new session
                //if authentication succeeds, identity will be saved automatically
                
                try{
                    $result = $auth->authenticate($adapter);
                    
                    if( $result->isValid() ){
                        
                        $user = new Users();
                        
                        $auth->setStorage( $session );
                        $session = $auth->getStorage();
                        $session->write($adapter->getResultRowObject(null, array("password")));

                        $resultObj = $adapter->getResultRowObject(array("id"));

                        $user->update(
                            array( "active" => "1"),//fields 
                            array("id" => $resultObj->id)//where
                        );
                        $this->_redirect("/dashboard");
                    }else throw new Exception( $result->getMessages()[0]);

                } catch(Exception $e){

                    $form->populate($formData);
                    echo "<p>" . $e->getMessage() . "</p>";
            
                }

            }

        }

    }

    public function logoutAction(){

        $id = Zend_Auth::getInstance()->getStorage();

        var_dump( $id->read()->rowid );
        return;
        $user = new Users();
        $user->update(array("active" => 0), array("id" => 1));

        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("/auth/login");
    }

}