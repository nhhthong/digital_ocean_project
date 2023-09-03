<?php

class Application_Model_LeaveDetail extends Zend_Db_Table_Abstract{

    protected $_name = 'leave_detail';

    public function _selectAdmin($limit = 20, $page = 1, $params = array())
    {
        $db = Zend_Registry::get('db');
        $arrCols = array(
            new Zend_Db_Expr('ld.*
                , st.code as code 
                , t1.name as `department_name`
                , t2.name as `team_name`
                '));
		
        $select = $db->select()
            ->from(array("ld" => $this->_name), $arrCols)
            ->join(array("st" => "staff"), "st.id = ld.staff_id", array("staff_name" => "concat(st.firstname,' ',st.lastname)"))
            ->join(array("lt" => "leave_type"), "lt.id = ld.leave_type", array("leave_type_note" => "lt.note"))
            ->join(array("lt2" => "leave_type"), "lt.parent = lt2.id", array("id_leave_group" => "lt2.id","leave_group" => "lt2.note"))
            ->join(array("t1" => "team"), "t1.id = st.department", array())
            ->join(array("t2" => "team"), "t2.id = st.team", array())
            ->join(array("t3" => "team"), "t3.id = st.title", array("title_name" => "t3.name"))
            ->joinLeft(array("ad" => "all_date"), "(ad.date BETWEEN ld.from_date AND ld.to_date) AND YEAR(ad.date) = YEAR(CURRENT_DATE())", array())
            ->joinLeft(array("ad2" => "all_date"), "ad2.date < NOW() AND (ad2.date BETWEEN ld.from_date AND ld.to_date) AND YEAR(ad2.date) = YEAR(CURRENT_DATE())", array());
        if(isset($params['list_staff']) && !empty($params['list_staff'])){
            $list_staff = $params['list_staff'];
            if(!empty($list_staff)) {
                $select->where("st.id in (?)", $list_staff);
            }
        }       

        if(isset($params['parent_leave_type']) && !empty($params['parent_leave_type']))
        {
            $select->where("lt2.id = ?", $params['parent_leave_type'] );
        }
        $select->group("ld.id");
        $select->order("ld.from_date desc");
        $select->order("st.code asc");

        if ($limit){
            $select->limitPage($page, $limit);
        }

        $data['data'] = $db->fetchAll($select);
        $data['total'] = $db->fetchOne("SELECT FOUND_ROWS()");
        return $data;
    }
}