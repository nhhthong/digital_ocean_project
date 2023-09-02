<?php
$this->_helper->layout->disableLayout();
$this->_helper->viewRenderer->setNoRender();
$id = $this->getRequest()->getParam('id');
if(!empty($id)){
    $QLeaveType = new Application_Model_LeaveType();
    echo json_encode($QLeaveType->getChild($id));
}else{
    echo json_encode(array());
}
exit;