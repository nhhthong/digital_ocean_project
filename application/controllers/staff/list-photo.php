<?php

$QStaff = new Application_Model_Staff();
$page   = $this->getRequest()->getParam("page", 1);
$params = [];
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