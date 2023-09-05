<?php
$month = $this->getRequest()->getParam('month', intval(date('m')));
$year  = $this->getRequest()->getParam('year', intval(date('Y')));
$number_day_of_month = date('t', mktime(0, 0, 0, $month, 1, $year));
$from  = $year . '-' . $month . '-01';
$to    = $year . '-' . $month . '-' . $number_day_of_month;

$params = [
    'month' => $month,
    'year'  => $year
];
$this->view->params = $params;