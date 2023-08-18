<?php

class My_Controller_Plugin_ACL extends Zend_Controller_Plugin_Abstract {

    protected $_defaultRole = 'guest';

    public function preDispatch(Zend_Controller_Request_Abstract $request) {                        
        $r    = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
        $auth = Zend_Auth::getInstance();   
        
        $acl  = new Zend_Acl();
        $default = array(
            'default::user::login',
            'default::user::logout',
            'default::user::auth',
            'default::user::noauth',
        );
        foreach ($default as $access) {
            $acl->add(new Zend_Acl_Resource($access));
        }        
        
        if ($auth->hasIdentity()) {
             
        } else {                  
            $acl->addRole(new Zend_Acl_Role($this->_defaultRole));            
            if (!$acl->has($request->getModuleName() . '::' . $request->getControllerName() . '::' . $request->getActionName()))
                $acl->add(new Zend_Acl_Resource($request->getModuleName() . '::' . $request->getControllerName() . '::' . $request->getActionName()));
            foreach ($default as $access) {
                $acl->allow($this->_defaultRole, $access);
            }
            if (!$acl->isAllowed($this->_defaultRole, $request->getModuleName() . '::' . $request->getControllerName() . '::' . $request->getActionName())) {
                $redirect_url = '/user/login';
                $r->gotoUrl($redirect_url)->redirectAndExit();
            }         
        }
    }

}
