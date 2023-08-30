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
			'fullname'   => "CONCAT(p.firstname, ' ', p.lastname)",
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

        if(!empty($_GET['dev'])){
            echo $select->__toString();
            exit;
        }

        $result = $db->fetchAll($select);
        $total = $db->fetchOne("select FOUND_ROWS()");
        return $result;
    }

    function get_cache() {
        $cache  = Zend_Registry::get('cache');
        $result = $cache->load($this->_name . '_cache');

        if ($result === false) {

            $data = $this->fetchAll();

            $result = array();
            if ($data) {
                foreach ($data as $item) {
                    $result[$item->id] = $item->firstname . ' ' . $item->lastname;
                }
            }
            $cache->save($result, $this->_name . '_cache', array(), null);
        }
        return $result;
    }

    public function getIDCardInfor($staff_id) {
        $db  = Zend_Registry::get('db');
        $sql = "SELECT 
                p.name AS place_issued, 
                st.id_place_province AS  id_place_province
                FROM `staff` AS `st`
                LEFT JOIN province AS `p` ON st.id_place_province = p.id OR st.id_citizen_province = p.id
                 WHERE st.id = $staff_id ";

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result[0];
    }
}