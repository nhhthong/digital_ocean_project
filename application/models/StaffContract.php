<?php
class Application_Model_StaffContract extends Zend_Db_Table_Abstract
{
	protected $_name = 'staff_contract';

	public function print($staff_id){        
        $db = Zend_Registry::get("db");
        $select = $db->select();
        $arrCols = array(
			'p.code',
			'fullname'   => "CONCAT(p.firstname, ' ', p.lastname)",
            'p.dob',
			'p.ID_number', 'p.ID_date'
        );
        $select->from(array('p' => 'staff'), $arrCols);
		$select->where('p.id = ?', $staff_id);

        if(!empty($_GET['dev'])){
            echo $select->__toString();
            exit;
        }
        $result = $db->fetchAll($select);
        return $result;
    }
}