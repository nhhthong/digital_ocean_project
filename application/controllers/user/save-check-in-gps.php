<?php
$this->_helper->viewRenderer->setNoRender();
$this->_helper->layout->disableLayout();

$flashMessenger = $this->_helper->flashMessenger;
$userStorage    = Zend_Auth::getInstance()->getStorage()->read();
$latitude       = $this->getRequest()->getParam('latitude', null);
$longitude      = $this->getRequest()->getParam('longitude', null);
$dev            = $this->getRequest()->getParam('dev', 0);
try {
    $db = Zend_Registry::get('db');
    $db->beginTransaction();
    $QTimeGps = new Application_Model_TimeGps();

    if ($dev) {
        $latitude = "10.7326689";
        $longitude = "106.6997696";
    }

    if(empty($latitude) || empty($longitude)) {
        $flashMessenger->setNamespace('error')->addMessage('Vui lòng bật gps!');
        $this->_redirect(HOST . 'user/check-in-gps'); exit;
    }

    // tọa độ Tôn Đức Thắng     
    $office_latitude  = "10.7326689";
    $office_longitude = "106.6997696";
    $distance_office  = My_DistanceGps::getDistanceOffice($latitude, $longitude, $office_latitude, $office_longitude);

    if($distance_office > 2){
        $flashMessenger->setNamespace('error')->addMessage('Bạn chấm công cách văn phòng vượt quá quy định. Vui lòng sử dụng 3g/4g để tăng độ chính xác!');
        $this->_redirect(HOST . 'user/check-in-gps'); exit;
    }

    $staff_id = $userStorage->id;
    $stmt     = $db->prepare('CALL pr_insert_check_in_gps (:p_staff_id, :p_latitude, :p_longitude)');
    $stmt->bindParam('p_staff_id', $staff_id, PDO::PARAM_INT);
    $stmt->bindParam('p_latitude', $latitude, PDO::PARAM_STR);     
    $stmt->bindParam('p_longitude', $longitude, PDO::PARAM_STR);     
    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt->closeCursor();

    $flashMessenger->setNamespace('success')->addMessage('Chấm công thành công!');    
    $db->commit();
} catch (Exception $e) { 
    $flashMessenger->setNamespace('error')->addMessage($e->getMessage());
    $db->rollBack();
}
$this->_redirect(HOST . 'user/check-in-gps'); 