<?php
class Application_Model_StaffDateoffReason extends Zend_Db_Table_Abstract
{
    protected $_name = 'staff_offdate_reason';
    function get_cache(){        
        $where   = [];
        $where[] = $this->getAdapter()->quoteInto('is_hidden = ? ', 0);
        $data    = $this->fetchAll($where, 'name');
        $result  = array();
        if ($data){
            foreach ($data as $item){
                $result[$item->id] = $item->name;
            }
        }
        return $result;
    }
}