<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);
$id = $this->getRequest()->getParam('id', NULL);
$QStaff = new Application_Model_Staff();

$where = null;
$where = $QStaff->getAdapter()->quoteInto('id = ?', $id);
$QStaff->update(
    [
        'is_locked_photo' => 1
    ], $where
);

$title   = "Thông báo từ hệ thống";
$content = "Nhân sự yêu cầu bạn đổi ảnh đại diện";
My_Notification::send($title, $content, $id, 1);

$flashMessenger = $this->_helper->flashMessenger;
$flashMessenger->setNamespace('success')->addMessage('Đã locked ảnh!');
$this->redirect('/staff/list-photo');