<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
echo '<link href="/css/bootstrap.min.css" rel="stylesheet">';
try {
    $db = Zend_Registry::get('db');
    $db->beginTransaction();

    $userStorage = Zend_Auth::getInstance()->getStorage()->read();
    $is_leave_half = $this->getRequest()->getParam("is-leave-half");
    $from = $this->converDate($this->getRequest()->getParam("from"));
    $to = $this->converDate($this->getRequest()->getParam("to"));
    $date = $this->converDate($this->getRequest()->getParam("date-leave-half"));
    $leave_type = $this->getRequest()->getParam("leave-type");
    $reason = $this->getRequest()->getParam("reason");
    $due_date = $this->converDate($this->getRequest ()->getParam ( 'due_date' ));

    if($from < $userStorage->joined_at){
        throw new Exception('Ngày phép trước ngày bắt đầu làm việc.');
    }
        
    if($leave_type == 17){
        if(empty($due_date)){
            throw new Exception ("Vui lòng nhập ngày dự sinh");
        }        
    }

    $sql_check_leave_time = "SELECT
        COUNT(ld.id) AS `check_leave_time`
        FROM `leave_detail` AS `ld`
        WHERE (
            (            
                ld.from_date <= '$from') AND (ld.to_date >= '$from')
                OR (ld.from_date <= '$to' AND ld.to_date >= '$to')
                OR (ld.from_date >= '$from' AND ld.to_date <= '$to')
            )
            AND (ld.staff_id = $staff_id)
            AND  (ld.hr_approved <> 2 AND ld.status <> 2)";
    $stmt = $db->prepare ( $sql_check_leav_time );
    $stmt->execute ();
    $result = $stmt->fetchAll ();
    if ($result) throw new Exception ("Đã có phép đăng ký trong khoản thời gian này");

    define('MAX_SIZE_UPLOAD', 3145728);            
    $name1 = "";
    if(!empty($_FILES['image']['name'])){
        $ext_arr = array('jpg','png','JPG','PNG','jpeg','JPEG');
        $ext1    = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        if(($_FILES['image']['size'] > MAX_SIZE_UPLOAD)){
            throw new Exception("Kích thước file vượt quá 3Mb");
        }

        if(!in_array($ext1, $ext_arr)){
            throw new Exception("Hình sai định dạng png, jpg, jpeg");
        }
               
        $name1= "leave_".$userStorage->id."_". time().".png";
        $file1 = APPLICATION_PATH . "/../public/photo/leave/" . $name1;
                
        if (!empty($_FILES['image']['name']) ) {
            $success1 = @move_uploaded_file($_FILES['image']['tmp_name'], $file1);                   
        }            
    }
            
    if($is_leave_half == 1){
        if(strtotime($from) > strtotime($to)){
            throw new Exception("Ngày bắt đầu nghỉ phép không được lơn hơn ngày kết thúc");
        }else{
            $data = array(
                'from'       => $from,
                'to'         => $to,
                'leave_type' => $leave_type,
                'staff_id'   => $staff_id,
                'reason'     => $reason,
            );
            if(!empty($name1)){
                $data['image'] = $name1;
            }
            if(!empty($due_date)){
                $data['due_date'] = $due_date;
            }
            $QLeaveDetail = new Application_Model_LeaveDetail();
            $QAllDate     = new Application_Model_AllDate();

            $total = $QAllDate->count($from, $to);
            $data['total'] = intval($total);
            $data['is_half_day'] = 0;
            $QLeaveDetail->insert($data);
        }
    }else{
        $data = array(
            'from'       => $date,
            'to'         => $date,
            'leave_type' => $leave_type,
            'staff_id'   => $staff_id,
            'reason'     => $reason,
        );
        if(!empty($name1)){
            $data['image'] = $name1;
        }
        if(!empty($due_date)){
            $data['due_date'] = $due_date;
        }
        $data['total'] = 0.5;
        $data['is_half_day'] = 1;
        $QLeaveDetail = new Application_Model_LeaveDetail();
        $QLeaveDetail->insert($data);
    }
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

