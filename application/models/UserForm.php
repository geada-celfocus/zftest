<?php

class UserForm extends Zend_Form{

    public function __construct($options = null){

        parent::__construct($options);
        $this->setName("User");

        $id = new Zend_Form_Element_Hidden("id");

        $name = new Zend_Form_Element_Text("name");
        $name->setLabel("Name")
        ->setRequired(true)
        ->addFilter("StripTags")
        ->addFilter("StringTrim")
        ->addValidator("NotEmpty");

        $username = new Zend_Form_Element_Text("username");
        $username->setLabel("Username")
        ->setRequired(true)
        ->addFilter("StripTags")
        ->addFilter("StringTrim")
        ->addValidator("NotEmpty");

        $password = new Zend_Form_Element_Password("password");
        $password->setLabel("Passsword")
        ->setRequired(true)
        ->addFilter("StripTags")
        ->addValidator("NotEmpty");

        $cpassword = new Zend_Form_Element_Password("confirmpassword");
        $cpassword->setLabel("Confirm Passsword")
        ->addFilter("StripTags")
        ->addValidator("NotEmpty");

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib("id", "submitbutton");
        $this->addElements( array($id, $name, $username, $password, $cpassword, $submit) );

    }

}