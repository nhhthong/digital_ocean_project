<?php
$page             = $this->getRequest()->getParam('page', 1);
$name             = $this->getRequest()->getParam('name',null);
$code             = $this->getRequest()->getParam('code',null);
$month            = $this->getRequest()->getParam('month', date('m'));
$year             = $this->getRequest()->getParam('year', date('Y'));
$department       = $this->getRequest()->getParam('department',null);
$team             = $this->getRequest()->getParam('team',null);
$title            = $this->getRequest()->getParam('title',null);
$dev              = $this->getRequest()->getParam('dev',0);
$search           = $this->getRequest()->getParam('search', 0);
if(!$search)  $search = $page > 1 ? 1 : 0;
$this->view->search   = $search;

$limit = 20;

$number_day_of_month = date('t', mktime(0, 0, 0, $month, 1, $year));
// $number_day_of_month = cal_days_in_month(CAL_GREGORIAN, intval($month), $year);
$month_tmp =  ($month < 10) ? '0'. $month : $month;
$from_date = $year . '-' . $month_tmp. '-01';
$to_date   = $year . '-' . $month_tmp . '-' . $number_day_of_month;

$params = [
    'month' => $month,
    'year'  => $year
];

$this->view->params = $params;