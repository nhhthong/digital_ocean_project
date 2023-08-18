<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
$flashMessenger = $this->_helper->flashMessenger;
$flashMessenger->setNamespace('error')->addMessage('Error!');
$this->_redirect(HOST . 'staff');
