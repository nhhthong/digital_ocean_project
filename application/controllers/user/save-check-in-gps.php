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

    if(empty($latitude) || empty($longitude)) {
        $flashMessenger->setNamespace('error')->addMessage('Vui lòng bật gps!');
        $this->_redirect(HOST . 'user/check-in-gps'); exit;
    }

    // tọa độ Tôn Đức Thắng     
    $office_latitude  = "10.7326689";
    $office_longitude = "106.6997696";
    $distance_office  = My_DistanceGps::getDistanceOffice($latitude, $longitude, $office_latitude, $office_longitude);

    if($distance_office > 2 && !$dev){
        $flashMessenger->setNamespace('error')->addMessage('Bạn chấm công cách văn phòng vượt quá quy định. Vui lòng sử dụng 3g/4g để tăng độ chính xác!');
        $this->_redirect(HOST . 'user/check-in-gps'); exit;
    }

    $staff_id   = $userStorage->id;
    $staff_code = $userStorage->code;
    $date       = date('Y-m-d');
    $to_date    = date("Y-m-t");
    $datetime   = date('Y-m-d H:i:s');
    
    $where_check   = [];
    $where_check[] = $QTimeGps->getAdapter()->quoteInto('staff_id = ?', $staff_id);
    $where_check[] = $QTimeGps->getAdapter()->quoteInto('check_in_day = ?', $date);
    $check_in      = $QTimeGps->fetchRow($where_check);

    $data_insert = array(
        'staff_id'         => $staff_id,
        'check_in_day'     => $date,
        'check_in_at'      => $datetime,
        'status'           => '1',
        'latitude'         => $latitude,
        'longitude'        => $longitude,
    );
    $QTimeGpsNew->insert($data_insert);
    $flashMessenger->setNamespace('success')->addMessage('Chấm công thành công!');    
    $db->commit();
} catch (Exception $e) { 
    $flashMessenger->setNamespace('error')->addMessage($e->getMessage());
    $db->rollBack();
}
$this->_redirect(HOST . 'user/check-in-gps'); 