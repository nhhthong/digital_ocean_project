<?php
class StaffTimeController extends My_Controller_Action { 
    
    public function staffViewAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'staff-view.php';
    }

    public function saveTempTimeAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'save-temp-time.php';
    }

    public function getReasonTmpTimeAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'get-reason-tmp-time.php';
    }    
}