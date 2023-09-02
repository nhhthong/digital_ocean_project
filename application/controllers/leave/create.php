<?php
$flashMessenger             = $this->_helper->flashMessenger;
$QLeaveType                 = new Application_Model_LeaveType();
$this->view->parent_type    = $QLeaveType->getParent();
$this->view->messages_error = $flashMessenger->setNamespace('error')->getMessages();
$this->view->messages       = $flashMessenger->setNamespace('success')->getMessages();