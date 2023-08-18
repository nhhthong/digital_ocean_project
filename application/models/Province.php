<?php
class Application_Model_Province extends Zend_Db_Table_Abstract {
    protected $_name = 'province';

    public function get_all2($id = NULL, $where_group_id = array(), $where_not_id = array()){
		$db = Zend_Registry::get('db');
		$select = $db->select()
			->from(array('p'=>$this->_name),array('p.*'))
			->order(array('p.name asc'))
        ;

        if($where_group_id){
            $select->where('id IN(?)',$where_group_id);
        }

        if($where_not_id){
            $select->where('id not IN(?)',$where_not_id);
        }

        if($id){
			$select->where('id = ?',$id);
		}

		$rs = $db->fetchAll($select);
		$result = array();
		foreach($rs as $value):
			$result[$value['id']] = str_pad($value['code'],2,'0',STR_PAD_LEFT).' - '.$value['name'];
		endforeach;

		return $result;
	}	
}