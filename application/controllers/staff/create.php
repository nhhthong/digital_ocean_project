<?php
$id                  = $this->getRequest()->getParam('id');
$QTeam               = new Application_Model_Team();
$QProvince           = new Application_Model_Province();
$QReligion           = new Application_Model_Religion();
$QNationality        = new Application_Model_Nationality();
$QStaffOffdateReason = new Application_Model_StaffDateoffReason();

if ($id) {
    $QStaff = new Application_Model_Staff();
    $staffRowset = $QStaff->find($id);
    $staff = $staffRowset->current();
    $this->view->staff = $staff;
}
$recursiveDeparmentTeamTitle = $QTeam->get_recursive_cache();
$provinces = $QProvince->get_all2(null, null, ID_PROVINCE_CITIZEN_IDENTITY_CARD);
$provinces_citizen = $QProvince->get_all2(null, ID_PROVINCE_CITIZEN_IDENTITY_CARD);

$this->view->recursiveDeparmentTeamTitle = $recursiveDeparmentTeamTitle;
$this->view->provinces = $provinces;
$this->view->provinces_citizen = $provinces_citizen;
$this->view->religions = $QReligion->fetchAll();
$this->view->nationalities = $QNationality->fetchAll();
$this->view->staff_dateoff_reason = $QStaffOffdateReason->get_cache();