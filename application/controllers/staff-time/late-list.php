<?php
$month = $this->getRequest()->getParam('month', intval(date('m')));
$year  = $this->getRequest()->getParam('year', intval(date('Y')));
$from  = $year . '-' . $month . '-01';
$to    = $year . '-' . $month . '-' . cal_days_in_month(CAL_GREGORIAN, $month, $year);

$params = [
    'month' => $month,
    'year'  => $year
];
$this->view->params = $params;