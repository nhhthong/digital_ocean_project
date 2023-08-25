<?php

class ManageController extends My_Controller_Action { 
    
    public function notificationAction()
    {
        require_once 'manage'.DIRECTORY_SEPARATOR.'notification.php';
    }

    public function notificationCreateAction()
    {
        require_once 'manage'.DIRECTORY_SEPARATOR.'notification-create.php';
    }
}