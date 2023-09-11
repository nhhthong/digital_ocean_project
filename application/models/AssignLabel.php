<?php
class Application_Model_AssignLabel extends Zend_Db_Table_Abstract
{
    protected $_name = 'assign_label';
    
    public function get_cache () {
        $cache  = Zend_Registry::get('cache');
        $result = $cache->load($this->_name . '_cache');
        if ($result === false) {
            $data   = $this->fetchAll();
            $result = array();
            if ($data) {
                foreach ($data as $item) {
                    $result[$item->variable] = $item->system_variable;
                }
            }
            $cache->save($result, $this->_name . '_cache', array(), null);
        }
        return $result;
    }
}