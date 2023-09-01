<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender();
$reason_id       = $this->getRequest()->getParam('reason_id');
$QReasonTempTime = new Application_Model_ReasonTempTime();
$where           = $QReasonTempTime->getAdapter()->quoteInto('id = ?', $reason_id);
echo json_encode($QReasonTempTime->fetchRow($where)->toArray());