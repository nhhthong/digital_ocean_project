<?php

class Application_Model_StaffTempNew extends Zend_Db_Table_Abstract
{
    protected $_name = 'staff_temp_new';

    public function is_exist($staff_id)
    {
        $db = Zend_Registry::get("db");
        $select = $db->select()
            ->from(['s' => 'staff_temp_new'], ['s.*'])
            ->where('s.staff_id = ?', $staff_id)
            ->where("(s.is_deleted = 0 AND is_rejected = 0 AND is_approved = 0)", 1);
        $result = $db->fetchRow($select);
        return $result ? $result : false;
    }


    public function getListUpdateInfor($approve_type)
    {
        $db = Zend_Registry::get('db');

        $sql = "SELECT 
    			stn.id AS id,
    			st.id AS staff_id,
    			CONCAT(st.firstname, ' ', st.lastname) AS fullname,
		    	st.code AS code, 
		    	d.name AS department,
		    	t.name AS team,
		    	c.name AS title,
		    	st.joined_at AS join_date,
		    	st.id AS staff_id,
		    	st.phone_number AS phone_number,
		    	st.email AS email,
		    	stn.is_approved AS is_approved,
		    	stn.is_rejected AS is_rejected
		    	FROM staff_temp_new AS stn
		    	LEFT JOIN staff AS st ON stn.staff_id = st.id
		    	LEFT JOIN team AS d ON st.department = d.id
		    	LEFT JOIN team AS t ON st.team = t.id
		    	LEFT JOIN team AS c ON st.title = c.id
		    	LEFT JOIN regional_market AS rm ON st.regional_market = rm.id
		    	LEFT JOIN area AS a ON rm.area_id = a.id
		    	LEFT JOIN company AS cp ON st.company_id = cp.id
		    	WHERE is_approved = $approve_type
		    	ORDER BY is_rejected DESC  
            ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }

    public function getStaff($id)
    {
        $db = Zend_Registry::get("db");
        $select = $db->select()
            ->from(['stn' => 'staff_temp_new'], [
                'stn.*',
                'staff_name' => "CONCAT(s.firstname,' ', s.lastname)",
                'staff_code' => 's.code',
                'approve_by_name' => "CONCAT(st.firstname,' ', st.lastname)",
                'approve_by_code' => 'st.code',
                'reject_by_name' => "CONCAT(r.firstname,' ', r.lastname)",
                'reject_by_code' => 'r.code'
            ])
            ->joinLeft(['st' => 'staff'], 'stn.approve_by = st.id', [])
            ->joinLeft(['r' => 'staff'], 'stn.reject_by = r.id', [])
            ->joinLeft(['s' => 'staff'], 'stn.staff_id = s.id', [])
            ->where('stn.id = ?', $id);

        $result = $db->fetchRow($select);

        return $result;
    }

    function fetchPagination($page, $limit, &$total, $params)
    {
        $db = Zend_Registry::get('db');
        $select = $db->select();
        $select->from(array('s' => $this->_name), array(new Zend_Db_Expr('SQL_CALC_FOUND_ROWS DISTINCT s.id'), 's.*'));
        $select->joinLeft(array('p' => 'staff'), 's.staff_id = p.id', array(
            'p.email',
            'p.code',
            'p.joined_at',
            'fullname' => "CONCAT(p.firstname, ' ', p.lastname)",
        ))
        ->joinLeft(array('d' => 'team'), 'p.department = d.id', array('department' => 'd.name'))
        ->joinLeft(array('t' => 'team'), 'p.team = t.id', array('team' => 't.name'))
        ->joinLeft(array('c' => 'team'), 'p.title = c.id', array('title' => 'c.name'));
        $select->where('s.is_deleted = 0 OR s.is_deleted IS NULL');


        if (isset($params['code']) and $params['code'])
            $select->where('p.code LIKE ?', '%' . $params['code'] . '%');

        if ($limit)
            $select->limitPage($page, $limit);

        $select->order('s.created_at DESC');
        $result = $db->fetchAll($select);
        
        if ($limit)
            $total = $db->fetchOne("select FOUND_ROWS()");
        return $result;
    }

    public function onlyPhoneNummberHasChanged($oldInfo, $newInfo)
    {
        if (
            $oldInfo['phone_number'] != $newInfo['phone_number']
            && $oldInfo ['ID_number'] == $newInfo ['ID_number']
            && $oldInfo ['id_place_province'] == $newInfo ['id_place_province']
            && $oldInfo ['ID_date'] == $newInfo ['ID_date']
            && $oldInfo ['id_photo'] == $newInfo ['id_photo']
            && $oldInfo ['id_photo_back'] == $newInfo ['id_photo_back']
            && $oldInfo ['nationality'] == $newInfo ['nationality']
            && $oldInfo ['religion'] == $newInfo ['religion']
        ) {
            return true;
        }
        return false;
    }
}