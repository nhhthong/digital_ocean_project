<?php
class Application_Model_Team extends Zend_Db_Table_Abstract
{
	protected $_name = 'team';

    function fetchPagination($page, $limit, &$total, $params){
        $db = Zend_Registry::get('db');
        $select = $db->select()->from(array('p' => $this->_name),
                array(new Zend_Db_Expr('SQL_CALC_FOUND_ROWS p.id'), 'p.*'));

        if (isset($params['name']) and $params['name'])
            $select->where('p.name LIKE ?', '%'.$params['name'].'%');

        $select->order('p.name', 'COLLATE utf8_unicode_ci ASC');
        if ($limit)
        	$select->limitPage($page, $limit);

        $result = $db->fetchAll($select);
        $total = $db->fetchOne("select FOUND_ROWS()");
        return $result;
    }

    function get_cache(){
        $data = $this->fetchAll(null, 'name');
        $result = array();
        if ($data){
            foreach ($data as $item){
                $result[$item->id] = $item->name;
            }
        }          
        return $result;
    }    

    function get_recursive_cache(){    
        $where = $this->getAdapter()->quoteInto('del = ? ', 0);
        $data  = $this->fetchAll($where, 'name');
        $result = array();
        if ($data->count()){
            $data   = $data->toArray();
            $result = $this->formatTree($data, 0);
        }
        return $result;
    }

    function formatTree($tree, $parent){
        $tree2 = array();
        foreach($tree as $i => $item){
            if($item['parent_id'] == $parent){
                $tree2[$item['id']] = $item;
                $tree2[$item['id']]['children'] = $this->formatTree($tree, $item['id']);
            }
        }
        return $tree2;
    }
}