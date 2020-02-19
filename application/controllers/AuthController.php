<?php

class AuthController extends Zend_Controller_Action
{

    // fast password hashing, for testing only
    public static function hash_password($password, $salt = null)
    {
        if ($salt === null) {
            $salt = "/�#�d�n�J�{�:aO";
        }

        return password_hash($password, PASSWORD_BCRYPT, ["salt" => $salt]);
    }

    //this function is to be called by zend callback adapter, with provides password and hash
    public static function check_password($password, $hash)
    {
        return password_verify($password, $hash);
    }

    public function loginAction()
    {

        //creates the form object to be used in the view
        $form = new LoginForm();//LoginForm is under models dir
        $form->submit->setLabel("Login");
        $this->view->form = $form;
        
        if ($this->_request->isPost()) {
            global $config;//get access to the config file
            $auth = Zend_Auth::getInstance();

            $formData = $this->_request->getPost();
            if ($form->isValid($formData)) {//valid acconding to the form <input> tag

                // get info from the form
                $username = $this->_request->getPost("username");
                $password = $this->_request->getPost("password");

                //set table adapter
                // Add.: Database configuration is defined in the bootstrap
                $adapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(), $config->db->params->table->users, "username", "password");
                $adapter->setIdentity($username)
                ->setCredential(AuthController::hash_password($password));
                
                $session = new Zend_Auth_Storage_Session("identity");//start session under the name identity

                try {
                    $result = $auth->authenticate($adapter);
                    
                    if ($result->isValid()) {
                        $user = new Users();
                        
                        // save user identity in the session storage
                        $auth->setStorage($session);
                        $session = $auth->getStorage();
                        $session->write($adapter->getResultRowObject(null, array("password")));//get user data, without password

                        // get user id
                        $resultObj = $adapter->getResultRowObject(array("id"));

                        // set user state as active
                        $user->update(
                            array( "active" => "1"),//fields
                            array("id" => $resultObj->id)//where
                        );
                        $this->_redirect("/dashboard");
                    } else {
                        throw new Exception($result->getMessages()[0]);
                    }// print error messages
                } catch (Exception $e) {
                    $form->populate($formData);//set email back in the <input> field
                    echo "<p>" . $e->getMessage() . "</p>";
                }
            }
        }
    }

    public function logoutAction()
    {

        //$id = Zend_Auth::getInstance()->getStorage();

        /*var_dump( $id->read()->rowid );    |
        return;                              | } Not working for now
        $user = new Users();                 |
        $user->update(array("active" => 0), array("id" => 1)); | */

        Zend_Auth::getInstance()->clearIdentity();//deletes the existing credentials
        $this->_redirect("/auth/login");
    }
}
