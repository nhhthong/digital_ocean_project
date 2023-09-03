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
    
    public function lateListAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'late-list.php';
    }
    
    public function listStaffApproveAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'list-staff-approve.php';
    }

    public function getViewDetailApproveAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'get-view-detail-approve.php';
    }

    public function listStaffCheckInAction () {
        require_once 'staff-time' . DIRECTORY_SEPARATOR . 'list-staff-check-in.php';
    }
}