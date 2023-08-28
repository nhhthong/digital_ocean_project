<?php
class AjaxController extends My_Controller_Action { 
    public function loadTeamAction() {
        $QTeam   = new Application_Model_Team();
        $department_id = $this->getRequest()->getParam('department_id');        

        if (is_array($department_id) && count($department_id) > 0)
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id IN (?)', $department_id);
        elseif (is_numeric($department_id)) {
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $department_id);
        } else {
            $where[] = $QTeam->getAdapter()->quoteInto('1 = 0', 1);
        }

        $where[] = $QTeam->getAdapter()->quoteInto('is_hidden = ?', 0);
        $where[] = $QTeam->getAdapter()->quoteInto('del = ?', 0);
        echo json_encode($QTeam->fetchAll($where, 'name')->toArray());
        exit;
    }

    public function loadTitleAction() {
        $QTeam   = new Application_Model_Team();
        $team_id = $this->getRequest()->getParam('team_id');        
        $where   = array();
        
        if (is_array($team_id) && count($team_id) > 0)
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id IN (?)', $team_id);
        elseif (is_numeric($team_id)) {
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $team_id);
        } else {
            $where[] = $QTeam->getAdapter()->quoteInto('1=0', 1);
        }

        $where[] = $QTeam->getAdapter()->quoteInto('del = ?', 0);
        $where[] = $QTeam->getAdapter()->quoteInto('is_hidden = ?', 0);
        echo json_encode($QTeam->fetchAll($where, 'name')->toArray());
        exit;
    }

    public function getTeamAction () {
        $departmentID = $this->getRequest()->getParam('department_id');
        $QTeam = new Application_Model_Team();
        $whereTeam = array();
        $whereTeam[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $departmentID);
        $whereTeam[] = $QTeam->getAdapter()->quoteInto('del = ? OR del IS NULL', 0);
        $Teams = $QTeam->fetchAll($whereTeam);
        if ($Teams) {
            echo json_encode($Teams->toArray());
        }
        exit;
    }

    
    public function getJobTitleAction() {
        $teamID = $this->getRequest()->getParam('team_id');
        $QTeam = new Application_Model_Team();
        $whereJobTile = array();
        $whereJobTile[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $teamID);
        $whereJobTile[] = $QTeam->getAdapter()->quoteInto('del = ? OR del IS NULL', 0);
        $jobTitle = $QTeam->fetchAll($whereJobTile);
        if ($jobTitle) {
            echo json_encode($jobTitle->toArray());
        }
        exit;
    }

    public function changeLangDefaultAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $lang     = $this->getRequest()->getParam('lang');
        $response = array('status' => 0);
        if (in_array($lang, array(1, 2, 3))) {
            $auth        = Zend_Auth::getInstance();
            $userStorage = Zend_Auth::getInstance()->getStorage()->read();
            $QStaff      = new Application_Model_Staff();
            $data_update = array('defaut_language' => $lang);
            $where       = $QStaff->getAdapter()->quoteInto('id = ?', $userStorage->id);
            $result      = $QStaff->update($data_update, $where);
            if ($result) {
                $userStorage->defaut_language = $lang;
                $response                     = array('status' => 1, 'message' => 'Thành công');
                $auth->getStorage()->write($userStorage);
                setcookie('defaut_lang', $lang, time() + (1440), "/", COOKIE_LAYOUT);
            }
        }
        echo json_encode($response);
        exit();
    }

    public function countNotiAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();
        if ($userStorage && isset($userStorage->id) && intval($userStorage->id)) {
            $db   = Zend_Registry::get('db');
            $select_count_noti = $db->select()->from(array('p' => 'notification_access'), array('p.*'));
            $select_count_noti->join('notification', 'p.notification_id = notification.id');
            $select_count_noti->where('p.user_id = ?', $userStorage->id);
            $select_count_noti->where('p.notification_status = ?', 0);
            $select_count_noti->where('notification.status <> 0');
            $select_count_noti->group('p.id');
            $select_count_noti->order('p.notification_status');
            $select_count_noti->order('p.id DESC');
            $result = $db->fetchAll($select_count_noti);
            echo json_encode(count($result));
            exit();
        }
    }

    public function loadNotiAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();
        if ($userStorage && isset($userStorage->id) && intval($userStorage->id)) {
            $db   = Zend_Registry::get('db');
            $select_list_noti = $db->select()->from(array('p' => 'notification_access'), array('p.id', 'notification.title', 'p.notification_status'));
            $select_list_noti->join('notification', 'p.notification_id = notification.id', array('p.notification_id'));
            $select_list_noti->where('p.user_id = ?', $userStorage->id);
            $select_list_noti->where('notification.status <> 0');
            $select_list_noti->group('p.id');
            $select_list_noti->order('p.notification_status');
            $select_list_noti->order('p.id DESC');
            $result = $db->fetchAll($select_list_noti);
            echo json_encode($result);
            exit();
        }
    }

    public function passByNotiAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $userStorage         = Zend_Auth::getInstance()->getStorage()->read();
        $QNotificationAccess = new Application_Model_NotificationAccess();
        if ($userStorage && isset($userStorage->id) && intval($userStorage->id)) {
            $where   = array();
            $where[] = $QNotificationAccess->getAdapter()->quoteInto('user_id = ?', $userStorage->id);
            $data    = array(
                'passby_popup' => 1
            );
            $QNotificationAccess->update($data, $where);                
        }
        $response = array('status' => 1, 'message' => 'Thành công');
        echo json_encode($response);
        exit();
    }
}