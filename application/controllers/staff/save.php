<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
echo '<link href="/css/bootstrap.min.css" rel="stylesheet">';
try {
    $db = Zend_Registry::get('db');
    $db->beginTransaction();
    $userStorage = Zend_Auth::getInstance()->getStorage()->read();
    $QStaff      = new Application_Model_Staff();
    
    $id = $this->getRequest()->getParam('id', null);    
    $full_name = $this->getRequest()->getParam('full_name', null);
    $gender = $this->getRequest()->getParam('gender', 0);
    $note = $this->getRequest()->getParam('note', 0);
    $department = $this->getRequest()->getParam('department', 0);
    $team = $this->getRequest()->getParam('team', 0);
    $title = $this->getRequest()->getParam('title', 0);
    $joined_at = $this->getRequest()->getParam('joined_at', null);
    $off_date = $this->getRequest()->getParam('off_date', null);
    $date_off_purpose_reason = $this->getRequest()->getParam('date_off_purpose_reason', 0);
    $date_off_purpose_detail = $this->getRequest()->getParam('date_off_purpose_detail', null);
    $off_type = $this->getRequest()->getParam('off_type', null);
    $status = $this->getRequest()->getParam('status', My_Staff_Status::Off);
    $dob = $this->getRequest()->getParam('dob', null);
    $id_place_province = $this->getRequest()->getParam('id_place_province', 0);
    $id_citizen_province = $this->getRequest()->getParam('id_citizen_province', 0);
    $ID_date = $this->getRequest()->getParam('ID_date', null);
    $nationality = $this->getRequest()->getParam('nationality', 0);
    $religion = $this->getRequest()->getParam('religion', 0);
    $del_photo = $this->getRequest()->getParam('del_photo', 0);
    $del_id_photo = $this->getRequest()->getParam('del_id_photo', 0);
    $del_id_photo_back = $this->getRequest()->getParam('del_id_photo_back', 0);
    $ID_number = $this->getRequest()->getParam('ID_number');
    $email = $this->getRequest()->getParam('email');

    if ($id_place_province>0 && $id_citizen_province>0){
        throw new Exception('Please choose a place !!!');
    }
    
    $full_name = mb_strtoupper($full_name, 'UTF-8');
    $pos = strripos(trim($full_name), " ");
    
    if (trim($pos)) {
        $firstname = substr(trim($full_name), 0, $pos);
        $lastname  = substr(trim($full_name), $pos + 1, strlen(trim($full_name)) - $pos);
    } else {
        $lastname = trim($full_name);
    }

    if ($email) {
        $email   = trim($email);
        $where   = array();
        $where[] = $QStaff->getAdapter()->quoteInto('email IS NOT NULL AND email LIKE ?', str_replace(EMAIL_SUFFIX, '', $email) . EMAIL_SUFFIX);
        if ($id)
            $where[] = $QStaff->getAdapter()->quoteInto('id <> ?', intval($id));
        $staff_check = $QStaff->fetchRow($where);
        if ($staff_check)
            throw new Exception("Email exists", 1);
    }

    if($off_date) $tmp = $this->_formatDate($off_date);
    $off_date_created_at = ($tmp) ? date('Y-m-d H:i:s') : NULL;

    $data = array(
        'department' => intval($department),
        'team' => intval($team),
        'firstname' => My_String::trim($firstname),
        'lastname' => My_String::trim($lastname),
        'title' => $title,
        'phone_number' => $phone_number,
        'joined_at' => $this->_formatDate($joined_at),
        'off_date' => $tmp,
        'gender' => $gender,  
        'off_type' => intval($off_type), 
        'ID_number' => $ID_number,  
        'ID_date' => $this->_formatDate($ID_date),  
        'nationality' => intval($nationality),
        'religion' => intval($religion),        
        'note' => $note,  
        'dob' => $dob,        
        'status' => intval($status),
        'id_place_province'   => intval($id_place_province),
        'id_citizen_province' => intval($id_citizen_province),
        'off_date_created_at' => $off_date_created_at,        
        'date_off_purpose_reason' => ($date_off_purpose_reason != '') ? $date_off_purpose_reason : null,
        'date_off_purpose_detail' => $date_off_purpose_detail,
        'email' => $email . EMAIL_SUFFIX
    );    
    $temp_dob = explode("/", $dob);
    if(strlen($ID_number) <> 9 && strlen($ID_number) <> 12) throw new Exception("Định dạng CCCD/CMND không hợp lệ");   
    if(!checkdate($temp_dob[1], $temp_dob[0], $temp_dob[2]))  throw new Exception("Định dạng ngày sinh không hợp lệ");
    $current_time = date('Y-m-d H:i:s');

    if (!$id) {  
        $ym = $this->_formatDate($joined_at);
        $ym = date('ym', strtotime($ym . ' 00:00:00'));
        $password           = empty($password) ? '123456' : $password;
        $data['password']   = md5($password);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userStorage->id;
        $id                 = $QStaff->insert($data);
        $QStaff->update(["code" => $ym . str_pad(substr($id, -4), 4, '0', STR_PAD_LEFT)], [
            $QStaff->getAdapter()->quoteInto("id = ?", $id)
        ]);
    }

    if ($id) { 
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userStorage->id;

        $uploaded_dir = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'photo' .
                DIRECTORY_SEPARATOR . 'staff' . DIRECTORY_SEPARATOR . $id;

        $file_uploaded_dir = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..' .
                DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'files' .
                DIRECTORY_SEPARATOR . 'staff' . DIRECTORY_SEPARATOR . $id;

        $arrPhoto = array(
            'photo'         => $uploaded_dir,
            'offdate_file'  => $file_uploaded_dir . DIRECTORY_SEPARATOR . 'off_date',
            'id_photo'      => $uploaded_dir . DIRECTORY_SEPARATOR . 'ID_Front',
            'id_photo_back' => $uploaded_dir . DIRECTORY_SEPARATOR . 'ID_Back',
        );

        $upload = new Zend_File_Transfer();
        $upload->setOptions(array('ignoreNoFile' => true));

        if (function_exists('finfo_file')) {            
            $upload->addValidator('Extension', false, 'jpg,jpeg,png,gif,doc,docx');
            $upload->addValidator('Size', false, array('max' => '2MB'));
            $upload->addValidator('ExcludeExtension', false, 'php,sh');
            $files = $upload->getFileInfo();
            $hasPhoto = false;
            $data = array();
            foreach ($arrPhoto as $key => $val) {
                $del = 'del_' . $key;
                if (isset($files[$key]['name'])) {
                    $hasPhoto = true;
                }
            }

            if ($hasPhoto) {
                if (!$upload->isValid()) {
                    $errors = $upload->getErrors();
                    $sError = null;
                    if ($errors and isset($errors[0]))
                        switch ($errors[0]) {
                            case 'fileUploadErrorIniSize':
                                $sError = 'File size is too large';
                                break;
                            case 'fileMimeTypeFalse':
                            case 'fileExtensionFalse':
                                $sError = 'The file(s) you selected weren\'t the type we were expecting';
                                break;
                            default:
                                $sError = 'The file(s) you selected weren\'t the type we were expecting';
                                break;
                        }

                    throw new Exception($sError);
                }

                foreach ($arrPhoto as $key => $val) {
                    $fileInfo = (isset($files[$key]) and $files[$key]) ? $files[$key] : null;
                    if (isset($fileInfo['name']) and $fileInfo['name']) {
                        if (!is_dir($val))
                            @mkdir($val, 0777, true);

                        $upload->setDestination($val);
                        $old_name = $fileInfo['name'];
                        $tExplode  = explode('.', $old_name);
                        $extension = strtolower(end($tExplode));
                        $new_name = 'UPLOAD-' . md5(uniqid('', true)) . '.' . $extension;
                        $upload->addFilter('Rename', array('target' => $val . DIRECTORY_SEPARATOR . $new_name));
                        $r = $upload->receive(array($key));
                        if ($r)
                            $data[$key] = $new_name;
                        else {
                            $messages = $upload->getMessages();
                            foreach ($messages as $msg)
                                throw new Exception($msg);
                        }
                    }
                }
            }
        }
        if ($del_photo) $data['photo'] = null;
        if ($del_id_photo) $data['id_photo'] = null;
        if ($del_id_photo_back) $data['id_photo_back'] = null;

        $where = $QStaff->getAdapter()->quoteInto('id = ?', $id);
        $QStaff->update($data, $where);
    } 

    $cache = Zend_Registry::get('cache');
    $cache->remove('staff_cache');
    $db->commit();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<script>window.parent.unblockUI();</script>';
    echo '<script>window.parent.redirectPage();</script>';
    echo '<div class="alert alert-success">Success</div>';
}catch (Exception $e) {
    $db->rollBack();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<script>window.parent.unblockUI();</script>';
    echo '<div class="alert alert-error">Failed - ' . $e->getMessage() . '</div>';
}

