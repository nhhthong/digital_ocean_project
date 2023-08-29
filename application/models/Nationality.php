<?php
class Application_Model_Nationality extends Zend_Db_Table_Abstract
{
	protected $_name = 'nationality';

	function get_cache(){
        $cache      = Zend_Registry::get('cache');
        $result     = $cache->load($this->_name.'_cache');
        if ($result === false) {
            $data = $this->fetchAll(null, 'name');
            $result = array();
            if ($data){
                foreach ($data as $item){
                    $result[$item->id] = $item->name;
                }
            }
            $cache->save($result, $this->_name.'_cache', array(), null);
        }
        return $result;
    }  
}