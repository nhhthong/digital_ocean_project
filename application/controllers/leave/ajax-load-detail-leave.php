<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender();

$id = $this->getRequest()->getParam('id');
if(!empty($id)) {
    $QLeaveType = new Application_Model_LeaveType();
    $info = $QLeaveType->findById($id);
    echo json_encode($info);
} else {
    echo json_encode(array());
}
 
 exit;