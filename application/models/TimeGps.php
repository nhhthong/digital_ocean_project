<?php
class Application_Model_TimeGps extends Zend_Db_Table_Abstract
{
    protected $_name = 'time_gps';

    public function getStaffView ($params = array())
    {     
        $db   = Zend_Registry::get('db');
        $sql  = "CALL `pr_get_staff_view`(:p_staff_id, :p_from_date, :p_to_date)";
        $stmt = $db->prepare($sql);
        $stmt->bindParam('p_staff_id', $params['staff_id'], PDO::PARAM_INT);
        $stmt->bindParam('p_from_date', $params['from_date'], PDO::PARAM_STR);
        $stmt->bindParam('p_to_date', $params['to_date'], PDO::PARAM_STR);
        $stmt->execute();
        $data = $stmt->fetchAll();   
        $stmt->closeCursor();
        $stmt = $db = null;
        return $data;
    }
}
