<?php
class Application_Model_Group extends Zend_Db_Table_Abstract
{
	protected $_name = 'group';
   
    function get_cache(){
        $cache      = Zend_Registry::get('cache');
        $result     = $cache->load($this->_name.'_cache');
        if ($result === false) {
            $data = $this->fetchAll();
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
