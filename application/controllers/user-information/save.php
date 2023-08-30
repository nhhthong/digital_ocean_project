<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
echo '<link href="/css/bootstrap.min.css" rel="stylesheet">';
try {
    $db = Zend_Registry::get('db');
    $db->beginTransaction();
    $ID_number = $this->getRequest()->getParam('ID_number');
    $phone_number = $this->getRequest()->getParam('phone_number');
    $id_place_province = $this->getRequest()->getParam('id_place_province');
    $ID_date = $this->getRequest()->getParam('ID_date');
    $nationality = $this->getRequest()->getParam('nationality');
    $religion = $this->getRequest()->getParam('religion');
    $userStorage = Zend_Auth::getInstance()->getStorage()->read();
    $staffId = $userStorage->id;

    $QStaff = new Application_Model_Staff();
    $QStaffTempNew = new Application_Model_StaffTempNew();
    $QPhoneNumberFirst = new Application_Model_PhoneNumberFirst();

    $currentStaff = $QStaff->fetchRow($QStaff->getAdapter()->quoteInto('id = ?', $staffId))->toArray();
    $currentPhoto = $currentStaff['photo'];
    $currentIdPhoto = $currentStaff['id_photo'];
    $currentIdPhotoBack = $currentStaff['id_photo_back'];

    foreach ($_FILES as $imageType => $imageInfor) {
        if (!$imageInfor['name']) {
            continue;
        }
        $targetDir = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' .
            DIRECTORY_SEPARATOR . 'public' . 
            DIRECTORY_SEPARATOR . 'photo' . 
            DIRECTORY_SEPARATOR . 'staff' . 
            DIRECTORY_SEPARATOR . $staffId;
        $extension = strtolower(pathinfo($imageInfor['name'], PATHINFO_EXTENSION));
        $newName   = 'UPLOAD-' . md5(uniqid('', true)) . '.' . $extension;
        $targets3  = 'photo/staff/'.$staffId;
    
        if ($imageType == 'photo') {
            $message   = 'Cập nhật ảnh thẻ thành công';
            $targetDir = $targetDir;
            $targets3  = $targets3;
        }
        if ($imageType == 'id_photo') {
            $message   = 'Cập nhật ảnh mặt trước CMND thành công';
            $targetDir = $targetDir . DIRECTORY_SEPARATOR . 'ID_Front';
            $targets3  = $targets3.'/ID_Front';
        }
        if ($imageType == 'id_photo_back') {
            $message   = 'Cập nhật ảnh mặt sau CMND thành công';
            $targetDir = $targetDir . DIRECTORY_SEPARATOR . 'ID_Back';
            $targets3  = $targets3.'/ID_Back';
        }
    
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0775, true);
        }
    
        $targetFile = $targetDir . DIRECTORY_SEPARATOR . $newName;

        if ($imageInfor["size"] > 5000000) {
            throw new Exception("Ảnh có dung lượng vượt quá 5MB");
        }

        if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
            throw new Exception("Ảnh sai định dạng");
        }

        if (move_uploaded_file($imageInfor["tmp_name"], $targetFile)) {
            // done
        } else {
            throw new Exception("Lỗi lưu ảnh");
        }
    }

    $oldInformation = [
        'ID_number'     => $currentStaff ['ID_number'],
        'ID_date'       => date('Y-m-d', strtotime($currentStaff ['ID_date'])),
        'id_photo'      => $currentStaff ['id_photo'],
        'id_photo_back' => $currentStaff ['id_photo_back'],
        'nationality'   => $currentStaff ['nationality'],
        'religion'      => $currentStaff ['religion'],
    ];

    $newInformation = [
        'ID_number'     => $ID_number,
        'ID_date'       => $ID_date ? date('Y-m-d', strtotime($ID_date)) : Null,
        'id_photo'      => $arrPhoto['id_photo'] ? $arrPhoto['id_photo'] : $currentStaff ['id_photo'],
        'id_photo_back' => $arrPhoto['id_photo_back'] ? $arrPhoto['id_photo_back'] : $currentStaff ['id_photo_back'],
        'nationality'   => $nationality,
        'religion'      => $religion,
    ];

    if($phone_number != $currentStaff ['phone_number']){
        $phone_number = trim($phone_number);
        if(empty($phone_number)){
            throw new Exception ('Vui lòng nhập thông tin SĐT');
        }
    
        if(!preg_match('/^[0-9]{10}+$/', $phone_number)) {
            throw new Exception ('SĐT không hợp lệ. Vui lòng kiểm tra lại');
        }
        $list_first_num     = $QPhoneNumberFirst->get_cache();
        $first_phone_number = substr($phone_number , 0 , 3);
        if(!in_array($first_phone_number , $list_first_num)){
            throw new Exception ('Đầu SĐT không hợp lệ. Vui lòng kiểm tra lại');
        }
        
        $where_staff       = [];
        $where_staff[]     = $QStaff->getAdapter()->quoteInto('phone_number = ?' , $phone_number);
        $where_staff[]     = $QStaff->getAdapter()->quoteInto('off_date IS NOT NULL' , 1);
        $phone_staff_exist = $QStaff->fetchRow($where_staff);

        if($phone_staff_exist){
            throw new Exception ('SĐT đã tồn tại. Vui lòng kiểm tra lại');
        }
    
        $QStaff->update([
            'phone_number' => $phone_number
        ], $QStaff->getAdapter()->quoteInto('id = ?', $staffId));
    }

    if (!$currentIdPhoto && !$currentIdPhotoBack) {
        if($arrPhoto['photo']){
            $QStaff->update([
                'photo' => $arrPhoto['photo']
            ], $QStaff->getAdapter()->quoteInto('id = ?', $staffId));
        }
        $QStaff->update($arrPhoto, $QStaff->getAdapter()->quoteInto('id = ?', $staffId));
    } else {   
        $edited_staff = $QStaffTempNew->is_exist($staffId);
        if ($oldInformation != $newInformation) { // check  user có thay đổi thông tin gì không
            if (!$edited_staff) {
                $data = [
                    'staff_id'          => $staffId,
                    'ID_number'         => $ID_number ? $ID_number : Null,
                    'id_place_province' => $id_place_province ? $id_place_province : Null,
                    'ID_date'           => $ID_date ? date('Y-m-d', strtotime(str_replace('/', '-', $ID_date))) : Null,
                    'phone_number'      => $phone_number ? $phone_number : null,
                    'nationality'       => $nationality ? $nationality : null,
                    'religion'          => $religion ? $religion : null,
                    'id_photo'          => $arrPhoto['id_photo'] ? $arrPhoto['id_photo'] : $currentIdPhoto,
                    'id_photo_back'     => $arrPhoto['id_photo_back'] ? $arrPhoto['id_photo_back'] : $currentIdPhotoBack,
                    'created_at'        => date('Y-m-d H:i:s'),
                ];
                $QStaffTempNew->insert($data);
            } else {
                throw new Exception ("Bạn có thông tin đang chờ duyệt");
            }
        }    
    }

    $db->commit();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<script>window.parent.unblockUI();</script>';
    echo '<div class="alert alert-success">Success</div>';
    echo '<script>window.parent.document.location.reload();</script>';
}catch (Exception $e) {
    $db->rollBack();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<script>window.parent.unblockUI();</script>';
    echo '<div class="alert alert-error">Failed - ' . $e->getMessage() . '</div>';
}