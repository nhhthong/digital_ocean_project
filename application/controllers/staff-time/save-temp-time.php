<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
echo '<link href="/css/bootstrap.min.css" rel="stylesheet">';

try {
    $db = Zend_Registry::get('db');
    $db->beginTransaction();
    $userStorage = Zend_Auth::getInstance()->getStorage()->read();
    $QTempTime   = new Application_Model_TempTime();
    $date        = $this->getRequest()->getParam('date', null);
    $office_time = $this->getRequest()->getParam('office_time', null);
    $add_reason  = $this->getRequest()->getParam('add_reason', null);
    $reason      = $this->getRequest()->getParam('reason_gps', null);

    $where_temp_time = array();
    $where_temp_time [] = $QTempTime->getAdapter()->quoteInto('staff_id = ?', $userStorage->id);
    $where_temp_time [] = $QTempTime->getAdapter()->quoteInto('date = ?', $date);
    $result_date = $QTempTime->fetchRow($where_temp_time);

    $data_temp_time = array(
        'staff_id'    => $userStorage->id,
        'date'        => $date,
        'office_time' => $office_time,
        'reason'      => $reason,
        'add_reason'  => $add_reason,
        'created_at'  => date('Y-m-d H:i:s'),
    );

    if (empty($result_date)) {
        $QTempTime->insert($data_temp_time);
    } else {
        $QTempTime->update($data_temp_time, $where_temp_time);
    }
    $db->commit();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<div class="alert alert-success">Success</div>';
    echo '<script>window.parent.document.location.reload();</script>';
}catch (Exception $e) {
    $db->rollBack();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<div class="alert alert-error">Failed - ' . $e->getMessage() . '</div>';
}