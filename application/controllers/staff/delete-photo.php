<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
$id = $this->getRequest()->getParam('id', NULL);
$QStaff = new Application_Model_Staff();

$where = null;
$where = $QStaff->getAdapter()->quoteInto('id = ?', $id);
$info  = $QStaff->fetchRow($where);

$uploaded_dir = APPLICATION_PATH . DIRECTORY_SEPARATOR . '..'
        . DIRECTORY_SEPARATOR . 'public' 
        . DIRECTORY_SEPARATOR . 'photo' 
        . DIRECTORY_SEPARATOR . 'staff'
        . DIRECTORY_SEPARATOR . $id
        . DIRECTORY_SEPARATOR . $info->photo;

$QStaff->update(
    [
        'photo' => null
    ], $where
);
unlink($uploaded_dir);
$flashMessenger = $this->_helper->flashMessenger;
$flashMessenger->setNamespace('success')->addMessage('Đã xóa ảnh!');
$this->redirect('/staff/list-photo');