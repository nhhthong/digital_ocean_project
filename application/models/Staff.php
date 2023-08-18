<?php
class Application_Model_Staff extends Zend_Db_Table_Abstract
{
	protected $_name = 'staff';

	public function fetchPagination($page, $limit, &$total, $params){        
        $db = Zend_Registry::get("db");
        $select = $db->select();
        $arrCols = array(
            new Zend_Db_Expr('SQL_CALC_FOUND_ROWS DISTINCT p.id'),
			'p.code',
			'fullname'   => 'CONCAT(p.firstname, " ", p.lastname)',
			'department' => 't1.name',
			'team'       => 't2.name',
			'title'      => 't3.name',
			'p.phone_number',
			'p.email', 'p.photo'
        );
        $select->from(array('p' => $this->_name), $arrCols);
        $select->joinLeft(array('t1' => 'team'), 't1.id = p.department', array());
		$select->joinLeft(array('t2' => 'team'), 't2.id = p.team', array());
		$select->joinLeft(array('t3' => 'team'), 't3.id = p.title', array());   
        if(empty($params['export'])){
            $select->limitPage($page, $limit);
        }
        $result = $db->fetchAll($select);
        $total = $db->fetchOne("select FOUND_ROWS()");
        return $result;
    }
}