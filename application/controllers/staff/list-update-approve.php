<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender();
$flashMessenger = $this->_helper->flashMessenger;
$id = $this->getRequest()->getParam('id');
$staff_id = $this->getRequest()->getParam('staff_id');
$note = $this->getRequest()->getParam('note');
$approve = $this->getRequest()->getParam('approve');
$reject = $this->getRequest()->getParam('reject');
$delete = $this->getRequest()->getParam('delete');

$userStorage      = Zend_Auth::getInstance()->getStorage()->read();
$QStaffTempNew    = new Application_Model_StaffTempNew();
$QStaff           = new Application_Model_Staff();
$QStaffTempNewLog = new Application_Model_StaffTempNewLog();

if ($delete) {
    $QStaffTempNew->update([
        'is_deleted' => 1
    ], ['id = ?' => $id]);
    $flashMessenger->setNamespace('success')->addMessage('Đã xóa');
    $this->_redirect('/staff/list-update-info');
}
$db = Zend_Registry::get('db');
$db->beginTransaction();
try {
    
    $StaffRowSet    = $QStaff->find($staff_id);
    $original_staff = $StaffRowSet->current();
    $ID_card_infor  = $QStaff->getIDCardInfor($staff_id);
    $edited_staff   = $QStaffTempNew->getStaff($id);

    $oldInformation = [
        'phone_number'      => $original_staff ['phone_number'],
        'ID_number'         => $original_staff ['ID_number'],
        'id_place_province' => $original_staff ['id_place_province'],
        'ID_date'           => date('Y-m-d', strtotime($original_staff ['ID_date'])),
        'id_photo'          => $original_staff ['id_photo'],
        'id_photo_back'     => $original_staff ['id_photo_back'],
        'nationality'       => $original_staff ['nationality'],
        'religion'          => $original_staff ['religion'],
    ];

    $newInformation = [
        'phone_number'      => $edited_staff['phone_number'],
        'ID_number'         => $edited_staff['ID_number'],
        'id_place_province' => $edited_staff['id_place_province'],
        'ID_date'           => $edited_staff['ID_date'] ? date('Y-m-d', strtotime($edited_staff['ID_date'])) : null,
        'id_photo'          => $edited_staff['id_photo'],
        'id_photo_back'     => $edited_staff['id_photo_back'],
        'nationality'       => $edited_staff ['nationality'],
        'religion'          => $edited_staff ['religion'],
    ];

    if ($approve) {      
        $data = [
            'ID_number'           => $edited_staff['ID_number'] ? $edited_staff['ID_number'] : null,
            'ID_date'             => $edited_staff['ID_date'] ? date('Y-m-d', strtotime($edited_staff['ID_date'])) : null,
            'phone_number'        => $edited_staff['phone_number'] ? $edited_staff['phone_number'] : null,
            'photo'               => $edited_staff['photo'] ? $edited_staff['photo'] : null,
            'id_photo'            => $edited_staff['id_photo'] ? $edited_staff['id_photo'] : null,
            'id_photo_back'       => $edited_staff['id_photo_back'] ? $edited_staff['id_photo_back'] : null,
            'nationality'         => $edited_staff['nationality'] ? $edited_staff['nationality'] : null,
            'religion'            => $edited_staff['religion'] ? $edited_staff['religion'] : null,
        ];
        if (in_array($edited_staff['id_place_province'], [64, 65])) {
            $data['id_place_province'] = 0;
            $data['id_citizen_province'] = $edited_staff['id_place_province'];
        } else {
            $data['id_citizen_province'] = 0;
            $data['id_place_province'] = $edited_staff['id_place_province'];
        }

        $QStaff->update(
            $data, 
            ['id = ?' => $staff_id]
        );

        $QStaffTempNew->update([
            'is_approved' => 1,
            'note'        => $note,
            'is_rejected' => 0,
            'is_deleted'  => 1
        ], [
            'id = ?' => $id
        ]);

        $QStaffTempNewLog->insert([
            'staff_temp_new_id' => $id,
            'before'            => json_encode($oldInformation),
            'after'             => json_encode($newInformation),
            'created_at'        => date('Y-m-d H:i:s'),
            'created_by'        => $userStorage->id
        ]);

    } elseif ($reject) {        
        $QStaffTempNew->update([
            'note'        => $note,
            'is_rejected' => 1,
            'is_deleted'  => 1,
            'reject_by'   => $userStorage->id,
            'reject_at'   => date("Y-m-d H:i:s"),
        ], [
            'id = ?' => $id
        ]);
        $title   = "Thông báo từ hệ thống";
        $content = "Nhân sự từ chối yêu cầu đổi thông tin của bạn";
        My_Notification::send($title, $content, $staff_id, 1);
    }
    $db->commit();
    $flashMessenger->setNamespace('success')->addMessage('Success');
}catch (Exception $e) {
    $db->rollBack();
    $flashMessenger->setNamespace('error')->addMessage($e->getMessage());
}
$this->_redirect('/staff/list-update-info');
