<?php
$staff_id = $this->getRequest()->getParam('staff_id');
$id       = $this->getRequest()->getParam('id');

$userStorage = Zend_Auth::getInstance()->getStorage()->read();
$staff_id = My_Util::escape_string($staff_id);
$id = My_Util::escape_string($id);

$QProvince = new Application_Model_Province();
$QStaff = new Application_Model_Staff();
$QStaffTempNew = new Application_Model_StaffTempNew();
$QReligion = new Application_Model_Religion();
$QNationality = new Application_Model_Nationality();
$QReligion = new Application_Model_Religion();

$StaffRowSet = $QStaff->find($staff_id);
$original_staff = $StaffRowSet->current();
$provinces = $QProvince->get_all2();
$edited_staff = $QStaffTempNew->getStaff($id);
$ID_card_infor = $QStaff->getIDCardInfor($staff_id); 

$this->view->staff_id = $staff_id;
$this->view->id = $id;
$this->view->ID_card_infor = $ID_card_infor;
$this->view->provinces = $provinces;
$this->view->original_staff = $original_staff;
$this->view->edited_staff = $edited_staff;
$this->view->nationalities = $QNationality->get_cache();
$this->view->religions = $QReligion->get_cache();

$flashMessenger = $this->_helper->flashMessenger;
$messages_error = $flashMessenger->setNamespace('error')->getMessages();
$messages = $flashMessenger->setNamespace('success')->getMessages();
$this->view->messages = $messages;
$this->view->messages_error = $messages_error;
