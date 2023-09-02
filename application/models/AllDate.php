<?php
class Application_Model_AllDate extends Zend_Db_Table_Abstract
{
	protected $_name = 'all_date';

    public function count ($from, $to) {
        $db      = Zend_Registry::get("db");
        $select  = $db->select();
        $arrCols = array(
            'total' =>  new Zend_Db_Expr('COUNT(p.is_sunday)')
        );
        $select->from(array('p' => $this->_name), $arrCols);
        $select->where('p.date >= ?', $from);
        $select->where('p.date <= ?', $to);
        $select->where('p.is_sunday = ?', 0);
        $select->group('is_sunday');
        $result = $db->fetchRow($select);
        return $result['total'];
    }
}