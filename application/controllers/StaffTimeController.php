<?php
class StaffTimeController extends My_Controller_Action { 
    
    public function staffViewAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'staff-view.php';
    }
}