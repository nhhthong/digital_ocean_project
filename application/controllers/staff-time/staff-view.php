<?php
$userStorage = Zend_Auth::getInstance()->getStorage()->read();
$month               = $this->getRequest()->getParam('month', date('m'));
$year                = $this->getRequest()->getParam('year', date('Y'));
$number_day_of_month = cal_days_in_month(CAL_GREGORIAN, intval($month), $year);
$from_date           = $year . '-' . $month . '-01';
$to_date             = $year . '-' . $month . '-' . $number_day_of_month;

$QStaff   = new Application_Model_Staff();
$QTimeGps = new Application_Model_TimeGps();

$params = [
    'month'     => $month,
    'year'      => $year,
    'staff_id'  => $userStorage->id,
    'from_date' => $from_date,
    'to_date'   => $to_date
];
$StaffRowSet = $QStaff->find($userStorage->id);
$staff       = $StaffRowSet->current();
$list        = $QTimeGps->getStaffView($params);

$this->view->staff  = $staff;
$this->view->list   = $list;
$this->view->params = $params;