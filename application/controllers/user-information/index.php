<?php
$userStorage  = Zend_Auth::getInstance()->getStorage()->read();
$QStaff       = new Application_Model_Staff();
$QTeam        = new Application_Model_Team();
$QProvince    = new Application_Model_Province();
$QNationality = new Application_Model_Nationality();
$QReligion    = new Application_Model_Religion();
$QStaffTempNew = new Application_Model_StaffTempNew();

$edited_staff = $QStaffTempNew->is_exist($userStorage->id);
$provinces   = $QProvince->get_all2();
$team_cache  = $QTeam->get_cache();
$user_id     = $userStorage->id;
$StaffRowSet = $QStaff->find($user_id);
$staff = $StaffRowSet->current();
if (!$staff) $this->_redirect("/user/noauth");
$this->view->edited_staff  = $edited_staff;
$this->view->staff         = $staff;
$this->view->title         = $team_cache[$staff->title];
$this->view->provinces     = $provinces;
$this->view->religions     = $QReligion->get_cache();
$this->view->nationalities = $QNationality->get_cache();