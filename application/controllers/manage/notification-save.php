<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
echo '<link href="/css/bootstrap.min.css" rel="stylesheet">';
try {
    $db = Zend_Registry::get('db');
    $db->beginTransaction();
    $userStorage = Zend_Auth::getInstance()->getStorage()->read();
    $QNotification = new Application_Model_Notification();
    $QNotificationAccess = new Application_Model_NotificationAccess();
    $QStaff = new Application_Model_Staff();

    $id = $this->getRequest()->getParam('id');
    $title = $this->getRequest()->getParam('title');
    $content = $this->getRequest()->getParam('content');
    $all_staff = $this->getRequest()->getParam('all_staff', 0);
    $department_objects = $this->getRequest()->getParam('departments', array());
    $team_objects = $this->getRequest()->getParam('teams', array());
    $title_objects = $this->getRequest()->getParam('staff_titles', array());
    $pop_up = $this->getRequest()->getParam('pop_up', 0);
    $from = $this->getRequest()->getParam('from', null);
    $to = $this->getRequest()->getParam('to', null);

    $all_staff = intval($all_staff);
    $pop_up = intval($pop_up);

    $list_staff = [];
    if ($all_staff) {
        $where = [];
        $where = $QStaff->getAdapter()->quoteInto ('status = ?', 1);
        $where = $QStaff->getAdapter()->quoteInto ('off_date is null', 1);
        $list_staff = $QStaff->fetchAll ($where) ->toArray();
        $list_staff = array_column($array_staff, 'id');
    } else {
        $list_staff_title = $list_staff_team = $list_staff_department = [];
        if ($title_objects) {
            $whereTitle = [];
            $whereTitle = $QStaff->getAdapter()->quoteInto ('status = ?', 1);
            $whereTitle = $QStaff->getAdapter()->quoteInto ('off_date is null', 1);
            $whereTitle = $QStaff->getAdapter()->quoteInto ('title in (?)', $title_objects);
            $list_staff_title = $QStaff->fetchAll ($whereTitle) ->toArray();
            $list_staff_title = array_column($list_staff_title, 'id');
        }
       
        if ($team_objects) {
            $whereTeam = [];
            $whereTeam = $QStaff->getAdapter()->quoteInto ('status = ?', 1);
            $whereTeam = $QStaff->getAdapter()->quoteInto ('off_date is null', 1);
            $whereTeam = $QStaff->getAdapter()->quoteInto ('team in (?)', $team_objects);
            $list_staff_team = $QStaff->fetchAll ($whereTeam) ->toArray();
            $list_staff_team = array_column($list_staff_team, 'id');
        }
        
        if ($department_objects) {
            $whereDepartment = [];
            $whereDepartment = $QStaff->getAdapter()->quoteInto ('status = ?', 1);
            $whereDepartment = $QStaff->getAdapter()->quoteInto ('off_date is null', 1);
            $whereDepartment = $QStaff->getAdapter()->quoteInto ('department in (?)', $department_objects);
            $list_staff_department = $QStaff->fetchAll ($whereDepartment) ->toArray();
            $list_staff_department = array_column($list_staff_department, 'id');
        }
        $list_staff = array_unique(array_merge($list_staff_title,$list_staff_team,$list_staff_department ));
    }

    $data = array(
        'title' => $title,
        'summary' => $summary,
        'content' => $content,
        'status' => 1,
        'pop_up' => $pop_up >= 1 ? 1 : 0,
        'show_from' => $from ,
        'show_to' => $to ,
    );
   
    if ($id) {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_by'] = $userStorage->id;
        $where = $QNotification->getAdapter()->quoteInto('id = ?', $id);
        $QNotification->update($data, $where);

        $where = null;
        $where = $QNotificationAccess->getAdapter()->quoteInto("notification_id = ?", $id);
        $QNotificationAccess->delete($where);
    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['created_by'] = $userStorage->id;
        $id = $QNotification->insert($data);     
    }
    My_Controller_Action::insertAllrowDB($list_staff, 'notification_access', $db); 
    $where = null;
    $where = $QNotificationAccess->getAdapter()->quoteInto("notification_id IS NULL", 1);
    $QNotificationAccess->update(
        [
            'created_date' => date('Y-m-d H:i:s'),
            'notification_id' => $id,
            'notification_from' => $from,
            'notification_to' => $to 
        ], $where
    );

    $db->commit();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<script>window.parent.unblockUI();</script>';
    echo '<div class="alert alert-success">Success</div>';
}catch (Exception $e) {
    $db->rollBack();
    echo '<script>window.parent.document.getElementById("iframe").style.display = \'block\';</script>';
    echo '<script>window.parent.document.getElementById("iframe").height = \'40px\';</script>';
    echo '<script>window.parent.unblockUI();</script>';
    echo '<div class="alert alert-error">Failed - ' . $e->getMessage() . '</div>';
}
