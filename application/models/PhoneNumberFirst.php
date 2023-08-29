<?php
class Application_Model_PhoneNumberFirst extends Zend_Db_Table_Abstract
{
	protected $_name = 'phone_number_first';
    public function get_cache(){
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(array('p' => $this->_name),array('p.*'));
        $result = $db->fetchAll($select);
        $data = [];
        foreach($result as $item){
            $data[] = $item['number'];
        }
        return $data;
    }
}
