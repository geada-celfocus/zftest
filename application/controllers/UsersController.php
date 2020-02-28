<?php

class UsersController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $config = Zend_Registry::get("config");

        $this->view->title = "Users";

        $client;

        echo  $config->soap->wsdl->directory_path;

        try {
            $client = new Zend_Soap_Client($config->soap->wsdl->directory_path . "users.wsdl");
            $result = $client->getUsers();
            //$this->view->users = $result;
        } catch (SoapFault $s) {
            echo "<textarea>";
            echo $client->getLastRequest();
            echo "</textarea>";
            $this->view->soapFault = $s;
        } catch (Exception $e) {
            $this->view->error = $e;
        }
    }

    public function createAction()
    {

        // initialize form
        $form = new UserForm();
        $form->submit->setLabel("Create");

        $this->view->form = $form;

        if ($this->_request->isPost()) {
            $data = $this->_request->getPost();

            if ($form->isValid($data)) {
                include_once "AuthController.php";

                $user = new Users();
                $row = $user->createRow();//create and empty row
                $row->name = $this->_request->getPost("name");
                $row->username = $this->_request->getPost("username");
                $row->password = AuthController::hash_password($this->_request->getPost("password"));
                $row->save();//update the empty row data

                $this->_redirect("/auth/login");
            } else {
                $form->populate($data);
            }
        }
    }

    
    // temporary
    // Generates  a wsdl file according with the Soap_Service_User class
    public function generateAction()
    {
        $this->_helper->layout->setLayout('xml');// change the layotu template to
        // a xml file

        $autodiscover = new Zend_Soap_AutoDiscover();
        $autodiscover->setClass(Soap_Service_User::class);
        $response = $autodiscover->handle();

        // $autodiscover->setUri("http://127.0.0.1:45428/soap/users"); -> this
        // method does not exist in zend framework version 1.6.6,

        // $autodiscover->setServiceName("MySoapService"); This method
        // does not exist in zend framework version 1.6.6 :: Service name
        // is set automatically using class name + Service

        //$autodiscover->handle();
        //echo $wsdl;
        //$wsdl->dump("/documentation/Users.wsdl");
        //$dom = $wsdl->toDomDocument();
    }
}
