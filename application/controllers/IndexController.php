<?php

class IndexController extends My_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        $this->redirect(HOST . 'view-menu?id=383');
    }
}