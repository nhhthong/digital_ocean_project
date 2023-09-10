<?php
$QStaff         = new Application_Model_Staff();
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
$export         = $this->getRequest()->getParam("export", 0);

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
    'export' => $export,
];
$limit  = LIMITATION;
$total  = 0;
$result = $QStaff->fetchPagination($page, $limit, $total, $params);

if($export) {
    set_time_limit(0);
    error_reporting(~E_ALL);
    ini_set('display_error', 0);
    ini_set('memory_limit', -1);

    $header = array(
        'Code',
        'Full Name',
        'Department',
        'Team',
        'Title'
    );
    $contents = $data = [];
    foreach ($result as $item) { 
        $data = [
            $item['code'],
            $item['fullname'],
            $item['department'],
            $item['team'],
            $item['title']
        ];
        $contents[] = $data;
    }
    $filename = 'Staff List - ' . date('d-m-Y H-i-s') . '.xlsx';
    My_BoxSpout::excelBuilder($header, $contents, $filename);
}

$QTeam  = new Application_Model_Team();
$recursiveDeparmentTeamTitle = $QTeam->get_recursive_cache();
$this->view->recursiveDeparmentTeamTitle = $recursiveDeparmentTeamTitle;

$this->view->list   = $result;
$this->view->limit  = $limit;
$this->view->total  = $total;
$this->view->params = $params;
$this->view->desc   = $desc;
$this->view->sort   = $sort;
$this->view->url    = HOST . "/staff/index" . ( $params ? "?" . http_build_query($params) . "&" : "?" );
$this->view->offset = $limit * ($page - 1);