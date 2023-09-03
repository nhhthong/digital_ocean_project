<?php
$flashMessenger = $this->_helper->flashMessenger;
$QLeaveDetail = new Application_Model_LeaveDetail();

$params_insurance['parent_leave_type']  = 6;
$params_viec_rieng['parent_leave_type'] = 3;
$params_nam['parent_leave_type']        = 1;

$data_nam        = $QLeaveDetail->_selectAdmin(null, null, $params_nam);
$data_viec_rieng = $QLeaveDetail->_selectAdmin(null, null, $params_viec_rieng);
$data_insurance  = $QLeaveDetail->_selectAdmin(null, null, $params_insurance);

$this->view->data_nam = $data_nam['data'];
$this->view->data_viec_rieng = $data_viec_rieng['data'];
$this->view->data_insurance = $data_insurance['data'];
$messages = $flashMessenger->setNamespace('success')->getMessages();
$this->view->messages_success = $messages;
$messages_error = $flashMessenger->setNamespace('error')->getMessages();
$this->view->messages_error = $messages_error;