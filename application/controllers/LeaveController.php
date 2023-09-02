<?php

class LeaveController extends My_Controller_Action {

    public function createAction() {
        require_once 'leave' . DIRECTORY_SEPARATOR . 'create.php';
    }

    public function saveAction() {
        require_once 'leave' . DIRECTORY_SEPARATOR . 'save.php';
    }

    public function ajaxLoadDetailLeaveAction() {
        require_once 'leave' . DIRECTORY_SEPARATOR . 'ajax-load-detail-leave.php';
    }

    public function ajaxLoadChildLeaveTypeAction() {
        require_once 'leave' . DIRECTORY_SEPARATOR . 'ajax-load-child-leave-type.php';
    }

    private function converDate($date = '')
    {
        $array_date = explode("/", $date);
        if(count($array_date) == 3)
        {
            return $array_date[2] . '-' . $array_date[1] . '-' . $array_date[0];
        }
        return;
    }
}