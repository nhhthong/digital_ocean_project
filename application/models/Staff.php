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
			'p.email', 'p.photo', 'p.status'
        );
        $select->from(array('p' => $this->_name), $arrCols);
        $select->joinLeft(array('t1' => 'team'), 't1.id = p.department', array());
		$select->joinLeft(array('t2' => 'team'), 't2.id = p.team', array());
		$select->joinLeft(array('t3' => 'team'), 't3.id = p.title', array());   

        if (isset($params['name']) and $params['name']) {
            $select->where("CONCAT(p.firstname, ' ' ,p.lastname) LIKE ?", "%" . $params['name'] . "%");
        }
        
        if (isset($params['department']) and $params['department']) {
            if (is_array($params['department']) && count($params['department']) > 0) {
                $select->where('p.department IN (?)', $params['department']);
            } else {
                $select->where('1=0', 1);
            }
        }

        if (isset($params['team']) and $params['team']) {
            if (is_array($params['team']) && count($params['team']) > 0) {
                $select->where('p.team IN (?)', $params['team']);
            } else {
                $select->where('1=0', 1);
            }
        }

        if (isset($params['title']) and $params['title']) {
            if (is_array($params['title']) && count($params['title']) > 0) {
                $select->where('p.title IN (?)', $params['title']);
            } else {
                $select->where('1=0', 1);
            }
        }

        if (isset($params['joined_at_from']) and $params['joined_at_from']) {
            $arrFrom                 = explode('/', $params['joined_at_from']);
            $params['joined_at_from'] = $arrFrom[2] . '-' . $arrFrom[1] . '-' . $arrFrom[0];
            $select->where('p.joined_at >= ?', $params['joined_at_from']);
        }

        if (isset($params['joined_at_to']) and $params['joined_at_to']) {
            $arrFrom               = explode('/', $params['joined_at_to']);
            $params['joined_at_to'] = $arrFrom[2] . '-' . $arrFrom[1] . '-' . $arrFrom[0];
            $select->where('p.joined_at <= ?', $params['joined_at_to']);
        }

        if (isset($params['off_date_from']) and $params['off_date_from']) {
            $arrFrom                 = explode('/', $params['off_date_from']);
            $params['off_date_from'] = $arrFrom[2] . '-' . $arrFrom[1] . '-' . $arrFrom[0];
            $select->where('p.off_date >= ?', $params['off_date_from']);
        }

        if (isset($params['off_date_to']) and $params['off_date_to']) {
            $arrFrom               = explode('/', $params['off_date_to']);
            $params['off_date_to'] = $arrFrom[2] . '-' . $arrFrom[1] . '-' . $arrFrom[0];
            $select->where('p.off_date <= ?', $params['off_date_to']);
        }

        if (isset($params['code']) and $params['code'])
            $select->where('p.code LIKE ?', '%' . $params['code'] . '%');

        if (isset($params['gender']) and $params['gender']) {
            if ($params['gender'] == 1) {
                $select->where('p.gender = ?', 1);
            } else {
                $select->where('p.gender = ?', 0);
            }
        }

        if (isset($params['status']) and $params['status']) {
            if ($params['status'] == 1) {
                $select->where('p.status = ?', 1);
            } elseif ($params['status'] == 2) {
                $select->where('p.status = ?', 0);
            } elseif ($params['status'] == 3) {
                $select->where('1=1', 1);
            }
        } 
           
        
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

    public function getStaffApprove ($department) {
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(array('p' => 'staff'), array(
            'p.id'
        ));
        $select->where('p.department = ?', $department);
        $result = $db->fetchCol($select);
        return $result;
    }
}