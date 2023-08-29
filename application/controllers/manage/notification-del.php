<?php
$id = $this->getRequest()->getParam('id');
$QNotification = new Application_Model_Notification();
$where = $QNotification->getAdapter()->quoteInto('id = ?', $id);
$data = array('status' => 0);
$QNotification->update($data, $where);
$this->_redirect('/manage/notification');