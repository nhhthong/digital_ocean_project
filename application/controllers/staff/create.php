<?php
$id     = $this->getRequest()->getParam('id');
$QTeam  = new Application_Model_Team();
$QProvince = new Application_Model_Province();
$recursiveDeparmentTeamTitle = $QTeam->get_recursive_cache();
$this->view->recursiveDeparmentTeamTitle = $recursiveDeparmentTeamTitle;
$provinces = $QProvince->get_all2(null, null, ID_PROVINCE_CITIZEN_IDENTITY_CARD);
$this->view->provinces = $provinces;
$provinces_citizen = $QProvince->get_all2(null, ID_PROVINCE_CITIZEN_IDENTITY_CARD);
$this->view->provinces_citizen = $provinces_citizen;

$QReligion = new Application_Model_Religion();
$this->view->religions = $QReligion->fetchAll();

$QNationality = new Application_Model_Nationality();
$this->view->nationalities = $QNationality->fetchAll();

$QStaffOffdateReason = new Application_Model_StaffDateoffReason();
$this->view->staff_dateoff_reason = $QStaffOffdateReason->get_cache();