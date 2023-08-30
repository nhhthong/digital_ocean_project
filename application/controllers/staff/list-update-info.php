<?php
$sort = $this->getRequest()->getParam('sort', '');
$desc = $this->getRequest()->getParam('desc', 1);
$page = $this->getRequest()->getParam('page', 1);
$name = $this->getRequest()->getParam('name');
$export = $this->getRequest()->getParam('export');
$code = $this->getRequest()->getParam('code');
$limit = LIMITATION;
$userStorage = Zend_Auth::getInstance()->getStorage()->read();
$staff_title = $userStorage->title;

$QTeam               = new Application_Model_Team();
$QStaffTempNew       = new Application_Model_StaffTempNew();
$QStaff              = new Application_Model_Staff();

$params = array(
    'code' => $code,
);
$list               = $QStaffTempNew->fetchPagination($page, $limit, $total, $params);
$this->view->list   = $list;
$this->view->params = $params;
$this->view->limit  = $limit;
$this->view->total  = $total;
$this->view->offset = $limit * ($page - 1);
$this->view->url    = HOST . 'staff/list-update-infor' . ($params ? '?' . http_build_query($params) . '&' : '?');

$flashMessenger = $this->_helper->flashMessenger;
$messages_error = $flashMessenger->setNamespace('error')->getMessages();
$messages = $flashMessenger->setNamespace('success')->getMessages();
$this->view->messages = $messages;
$this->view->messages_error = $messages_error;
