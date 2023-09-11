<?php
$this->_helper->viewRenderer->setNoRender();
$this->_helper->layout->disableLayout();

try {
    $userStorage = Zend_Auth::getInstance()->getStorage()->read();
    $db = Zend_Registry::get('db');
    $db->beginTransaction();

    $staff_id = $userStorage->id;
    $stmt     = $db->prepare('CALL pr_insert_check_out_gps (:p_staff_id)');
    $stmt->bindParam('p_staff_id', $staff_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $stmt->closeCursor();

    $flashMessenger->setNamespace('success')->addMessage('Check out thành công!');    
    $db->commit();
} catch (Exception $e) { 
    $flashMessenger->setNamespace('error')->addMessage($e->getMessage());
    $db->rollBack();
}