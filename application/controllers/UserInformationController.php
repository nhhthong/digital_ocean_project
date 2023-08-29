<?php

class UserInformationController extends My_Controller_Action
{

    public function indexAction()
    {
        require_once 'user-information' . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function saveChangeInforAction()
    {
        require_once 'user-information' . DIRECTORY_SEPARATOR . 'save-change-infor.php';
    }
}
