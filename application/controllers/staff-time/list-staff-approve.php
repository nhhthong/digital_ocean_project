<?php
$userStorage         = Zend_Auth::getInstance()->getStorage()->read();
$month               = $this->getRequest()->getParam('month', date('m'));
$year                = $this->getRequest()->getParam('year', date('Y'));
$number_day_of_month = cal_days_in_month(CAL_GREGORIAN, intval($month), $year);
$from_date           = $year . '-' . $month . '-01';
$to_date             = $year . '-' . $month . '-' . $number_day_of_month;

$QStaff = new Application_Model_Staff();
$QTeam  = new Application_Model_Team();
$QLeaveDetail = new Application_Model_LeaveDetail();

$title  = $QTeam->find($data->title)->current();
$group  = $title->access_group;
if (!in_array($group, [1,2]) && $userStorage->id <> SUPERADMIN_ID) $this->_redirect (HOST . 'user/noauth');
$get_list_staff  = $QStaff->getStaffApprove($userStorage->department);

$params = [
    'month' => $month,
    'year'  => $year
];
$params['list_staff'] = $get_list_staff;
$data_leave = $QLeaveDetail->_selectAdmin(null, null, $params);

$list_of_subordinates = implode(",", $get_list_staff);
$db = Zend_Registry::get('db');
$sql = "CALL `pr_list_staff_time_approve`(:from_date, :to_date, :p_staff_id)";
$stmt = $db->prepare($sql);
$stmt->bindParam('from_date', $from_date, PDO::PARAM_STR);
$stmt->bindParam('to_date', $to_date, PDO::PARAM_STR);
$stmt->bindParam('p_staff_id', $list_of_subordinates, PDO::PARAM_STR);
$stmt->execute();
$list_staff_view = $stmt->fetchAll();
$stmt->closeCursor();
$this->view->list_staff_view = $list_staff_view;
$this->view->data_leave      = $data_leave['data'];
$this->view->params          = $params;