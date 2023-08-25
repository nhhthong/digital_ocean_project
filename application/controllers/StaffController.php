<?php

class StaffController extends My_Controller_Action {
    public function createAction() {
        require_once 'staff' . DIRECTORY_SEPARATOR . 'create.php';
    }

    public function indexAction() {
        require_once 'staff' . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function saveAction() {
        require_once 'staff' . DIRECTORY_SEPARATOR . 'save.php';
    }

    public function jobTitleAction() {
        require_once 'staff' . DIRECTORY_SEPARATOR . 'job-title.php';
    }

    public function listPhotoAction() {
        require_once 'staff' . DIRECTORY_SEPARATOR . 'list-photo.php';
    }

    private function _formatDate($date) {
        if (!$date)
            return null;

        $date = trim($date);

        $temp = explode('/', $date);
        $formatedDate = (isset($temp[2]) ? $temp[2] : '0000') . '-' . (isset($temp[1]) ?
                $temp[1] : '01') . '-' . (isset($temp[0]) ? $temp[0] : '01');
        return $formatedDate;
    }
}