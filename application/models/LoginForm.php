<?php

class LoginForm extends Zend_Form
{
    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->setName("Login");

        $username = new Zend_Form_Element_Text("username");
        $username->setLabel("username")
        ->addFilter("StringTrim")
        ->addFilter("StripTags")
        ->addValidator("NotEmpty");

        $password = new Zend_Form_Element_Password("password");
        $password->setLabel("password")
        ->addFilter("StripTags")
        ->addFilter("StringTrim")
        ->addValidator("NotEmpty");

        $submit = new Zend_Form_Element_Submit("submit");
        $submit->setAttrib("id", "submit");

        $this->addElements(array($username, $password, $submit));
    }
}
