<?php

class Application_Model_LeaveType extends Zend_Db_Table_Abstract{

    protected $_name = 'leave_type';
   
    public function findById($id)
    {
        $db = Zend_Registry::get('db');
        $stmt = $db->prepare("select * from leave_type where id = :id");
        $stmt->bindParam('id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch();
        $stmt->closeCursor();
        $stmt = $db = null;
        return $data;
    }

    public function getChild($parent_id = -1)
    {
        $db  = Zend_Registry::get('db');
        $sql = "select * from leave_type where status = 1 and parent = " . $parent_id;              
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $db = $stmt = null;
        return $data;
    }

    public function getParent($params = array())
    {
        $db  = Zend_Registry::get('db');
        $sql = "select * from leave_type where (parent is null or parent = 0) and status = 1";
        if(!empty($params['id'])){
            $sql .= " and id <> " . $params['id'];
        }
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
        $db = $stmt = null;
        return $data;
    }
}