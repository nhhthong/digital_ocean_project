<?php
class Application_Model_NotificationAccess extends Zend_Db_Table_Abstract
{
    protected $_name = 'notification_access';
	
    function fetchPaginationAccess($page, $limit, &$total, $params) {    	 
    	$db = Zend_Registry::get('db');
    	$select = $db->select();
    	$select->from(array('p' => $this->_name),
    			array(new Zend_Db_Expr('SQL_CALC_FOUND_ROWS p.id'), 'p.*'));    	
        $select->joinLeft(array('s' => 'staff'), 's.id = p.user_id', array());
		$select->join(array('n' => 'notification'), 'n.id = p.notification_id');
		if (isset($params['pop_up']) && $params['pop_up']){
			$select->where('n.pop_up = ?', $params['pop_up']);
		}
		$select->where('n.status <> 0');	

    	if (isset($params['status']) && $params['status'])
    		$select->where('p.notification_status = 0');    
    
    	if (isset($params['read'])) {
    		if ($params['read'])
    			$select->where('nr.notification_id IS NOT NULL', 1);
    		else
    			$select->where('nr.notification_id IS NULL', 1);
    	}
    	
    	if (isset($params['staff_id']) && $params['staff_id'])
    		$select->where('p.user_id = ?', $params['staff_id']);
    
    	$select->group('p.id');        
		$select->order('p.notification_status');
        $select->order('p.id DESC');
    	if($limit)
    		$select->limitPage($page, $limit);
    
    	$result = $db->fetchAll($select);    
    	$total = $db->fetchOne("select FOUND_ROWS()");
    	return $result;
    }

	

    function fetchPaginationAccessPopUp($params) {    	 
    	$db = Zend_Registry::get('db');
		$arrCols = [
			'n.id', 'n.created_at', 'n.content', 'n.title'
		];

    	$select = $db->select();
    	$select->from(array('p' => $this->_name), $arrCols);    	
        $select->joinLeft(array('s' => 'staff'), 's.id = p.user_id', array());            
        $select->join(array('n' => 'notification'), 'n.id=p.notification_id', array());    
		$select->joinLeft(array('nr' => 'notification_read'), 'n.id=nr.notification_id', array());    		
		$select->where('p.notification_status = 0');
        $select->where('n.pop_up = ?', 1);
        $select->where('p.passby_popup = 0');	
        $select->where('n.status <> 0');	    
        $select->where('nr.notification_id IS NULL', 1);
    	
    	if (isset($params['staff_id']) && $params['staff_id'])
    		$select->where('p.user_id = ?', $params['staff_id']);
    
		$select->order('p.notification_status');
        $select->order('p.id DESC');
    	$result = $db->fetchAll($select);
    	return $result;
    }

}