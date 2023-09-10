<?php
$flashMessenger = $this->_helper->flashMessenger;
$messages = $flashMessenger->setNamespace('success')->getMessages();
$messages_error = $flashMessenger->setNamespace('error')->getMessages();
$this->view->messages_success = $messages;
$this->view->messages_error = $messages_error;

$QStaff = new Application_Model_Staff();
$page           = $this->getRequest()->getParam("page", 1);
$name           = $this->getRequest()->getParam("name", null);
$code           = $this->getRequest()->getParam("code", null);
$status         = $this->getRequest()->getParam("status", 1);
$joined_at_from = $this->getRequest()->getParam("joined_at_from", null);
$joined_at_to   = $this->getRequest()->getParam("joined_at_to", null);
$off_date_from  = $this->getRequest()->getParam("off_date_from", null);
$off_date_to    = $this->getRequest()->getParam("off_date_to", null);
$department     = $this->getRequest()->getParam("department", null);
$team           = $this->getRequest()->getParam("team", null);
$title          = $this->getRequest()->getParam("title", null);
$gender         = $this->getRequest()->getParam("gender", 0);
$params = [
    'name' => $name,
    'code' => $code,
    'status' => $status,
    'joined_at_from' => $joined_at_from,
    'joined_at_to' => $joined_at_to,
    'off_date_from' => $off_date_from,
    'off_date_to' => $off_date_to,
    'department' => $department,
    'team' => $team,
    'title' => $title,
    'gender' => $gender,
];
$limit  = LIMITATION;
$total  = 0;
$result = $QStaff->fetchPagination($page, $limit, $total, $params);

$QTeam  = new Application_Model_Team();
$recursiveDeparmentTeamTitle = $QTeam->get_recursive_cache();
$this->view->recursiveDeparmentTeamTitle = $recursiveDeparmentTeamTitle;

$this->view->list   = $result;
$this->view->limit  = $limit;
$this->view->total  = $total;
$this->view->params = $params;
$this->view->desc   = $desc;
$this->view->sort   = $sort;
$this->view->url    = HOST . "/staff/list-photo" . ( $params ? "?" . http_build_query($params) . "&" : "?" );
$this->view->offset = $limit * ($page - 1);