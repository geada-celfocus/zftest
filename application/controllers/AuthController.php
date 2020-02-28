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

    //this function is to be called by zend callback adapter, with provides
    //password and hash
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

                //set table adapter Add.: Database configuration is defined in
                // the bootstrap
                $adapter = new Zend_Auth_Adapter_DbTable(
                    Zend_Db_Table::getDefaultAdapter(),
                    $config->db->params->table->users,
                    "username",
                    "password"
                );
                $adapter->setIdentity($username)
                    ->setCredential(AuthController::hash_password($password));

                try {
                    $result = $auth->authenticate($adapter);
                    
                    if ($result->isValid()) {

                        // zend framework, by default, saves user identity
                        // (a.k.a. username) in session storage altough, this
                        // way we have access to the user's complete info
                        // without needing aditional requests Also, this is the
                        // session storage relative to the authentication, for
                        // adicional data, Zend_Session_Namespace should be used
                        $storage = new Zend_Auth_Storage_Session();
                        $user = $adapter->getResultRowObject(null, array("password"));/* get user info(acording to
                        authentication) without password*/
                        $storage->write($user);//write user data to the storage
                        $auth->setStorage($storage);// set storage in the Zend_Auth
                        //this data can be accessed using $auth->getIdentity()
                        //method (stdObj will be returned)

                        $users = new Users();
                        // select() returns a RowSet, while fetch row returns a
                        // Row, wich can modify the row directly
                        $row = $users->fetchRow($users->select()->where('id = ?', $user->id));
                        $row->active = 1;//set user as active
                        $row->save();//update user data

                        // Start user session, usign the zend auth
                        $userSession = new Zend_Session_Namespace("Zend_Auth");/* starts session automatically,
                        Zend_Session::start() could also be used */
                        $userSession->setExpirationSeconds($config->resources->session->remember_me_seconds);// this will allow Zend_Auth to expire
                        $userSession->color = "#ff0000";// save data in session storage, to test


                        $this->_redirect("/dashboard");
                    } else {
                        throw new Exception($result->getMessages()[0]);// print error messages
                    }
                } catch (Exception $e) {
                    $form->populate($formData);//set email back in the <input> field
                    echo "<p>" . $e->getMessage() . "</p>";
                }
            }
        }
    }

    public function logoutAction()
    {
        $user = Zend_Auth::getInstance()->getIdentity();

        $users = new Users();
        $row = $users->fetchRow($users->select()->where("id", $user->id));
        $row->active = 0;
        $row->save();

        Zend_Auth::getInstance()->clearIdentity();//deletes the existing credentials
        $this->_redirect("/auth/login");
    }
}
