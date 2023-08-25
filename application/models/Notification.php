<?php
class Application_Model_Notification extends Zend_Db_Table_Abstract
{
    protected $_name = 'notification';

    function fetchPagination($page, $limit, &$total, $params) {
        $db          = Zend_Registry::get('db');
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();        
        $select = $db->select()
                ->from(array('p' => $this->_name), array(new Zend_Db_Expr('SQL_CALC_FOUND_ROWS p.id'), 'p.*'));
        
        if (isset($params['title']) and $params['title'])
            $select->where('p.title LIKE ?', '%' . $params['title'] . '%');
        
        if (isset($params['content']) and $params['content'])
            $select->where('p.content LIKE ?', '%' . $params['content'] . '%');

        if (isset($params['created_from']) && $params['created_from']) {
            $select->where('p.created_at >= ?', $params['created_from']);
        }

        if (isset($params['created_to']) && $params['created_to']) {
            $select->where('p.created_at <= ?', $params['created_to']);
        }
        $select->where('p.created_by = ?',$userStorage->id );
        $select->group('p.id');
        $select->order('created_at DESC');
           
        if ($limit)
            $select->limitPage($page, $limit);
        
        
        $result = $db->fetchAll($select);
        $total  = $db->fetchOne("select FOUND_ROWS()");
        return $result;
    }
}