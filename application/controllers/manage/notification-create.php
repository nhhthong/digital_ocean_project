<?php
$id = $this->getRequest()->getParam('id');

if ($id) {
    $QModel = new Application_Model_Notification();
    $QNotificationObject = new Application_Model_NotificationObject();

    $rowset = $QModel->find($id);
    $notification = $rowset->current();
    if (!$notification) $this->_redirect(HOST.'manage/notification');

    $this->view->notification = $notification;
    $QStaff = new Application_Model_Staff();
    
    $where               = $QNotificationObject->getAdapter()->quoteInto('notification_id = ?', $id);
    $old_objects         = $QNotificationObject->fetchAll($where);

    $old_department_objects = array();
    $old_team_objects       = array();
    $old_title_objects      = array();
    $all_staff              = 0;

    foreach ($old_objects as $_key => $_value) {
        switch ($_value['type']) {
            case My_Notification::DEPARTMENT:
                $old_department_objects[] = $_value['object_id'];
                break;

            case My_Notification::TEAM:
                $old_team_objects[] = $_value['object_id'];                    
                break;

            case My_Notification::TITLE:
                $old_title_objects[] = $_value['object_id'];                    
                break;

            case My_Notification::ALL_STAFF:
                $all_staff = $_value['object_id'];                    
                break;

            default:
                throw new Exception("Invalid object type");
                break;
        }
    }

    $this->view->old_department_objects = $old_department_objects;
    $this->view->old_team_objects       = $old_team_objects;
    $this->view->old_title_objects      = $old_title_objects;
    $this->view->all_staff              = $all_staff;
    $this->view->userStorage            = Zend_Auth::getInstance()->getStorage()->read();
}
$QTeam                 = new Application_Model_Team();
$this->view->team_cache = $QTeam->get_recursive_cache();

$flashMessenger       = $this->_helper->flashMessenger;
$messages             = $flashMessenger->setNamespace('error')->getMessages();
$this->view->messages = $messages;
$this->view->back_url = $this->getRequest()->getServer('HTTP_REFERER');