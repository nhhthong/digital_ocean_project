<?php
$this->_helper->layout->disableLayout();
$Qstaff   = new Application_Model_Staff();
$month    = $this->getRequest()->getParam('month');
$staff_id = $this->getRequest()->getParam('staff_id');
$year     = $this->getRequest()->getParam('year');

$number_day_of_month = cal_days_in_month(CAL_GREGORIAN, intval($month), $year);
$from_date = $year . '-' . $month . '-01 00:00:00';
$to_date = $year . '-' . $month . '-' . $number_day_of_month . ' 23:59:59';

$db = Zend_Registry::get('db');
$stmt = $db->prepare("CALL pr_list_staff_time_approve_by_id(:staff_id, :p_month, :p_year)");
$stmt->bindParam('staff_id', $staff_id, PDO::PARAM_INT);
$stmt->bindParam('p_month', $month, PDO::PARAM_INT);
$stmt->bindParam('p_year', $year, PDO::PARAM_INT);
$stmt->execute();
$data = $stmt->fetchAll();
$stmt->closeCursor();
$stmt = $db = null;
$this->view->data = $data;
$this->view->staff = $staff;