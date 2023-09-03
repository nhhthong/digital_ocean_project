<?php
class ViewMenuController extends My_Controller_Action
{

    public function indexAction()
    {
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();
        $lang        = $userStorage->defaut_language;
        $id          = $this->getRequest()->getParam('id');

        //lay menu 
        if (isset($userStorage->menu) && $userStorage->menu) {
            $groupMenu = $userStorage->menu;
        } else {
            $QMenu     = new Application_Model_Menu();
            $where     = null;
            $where     = $QMenu->getAdapter()->quoteInto('group_id = ? and hidden is null', 1);
            $groupMenu = $QMenu->fetchAll($where, array('parent_id', 'position'));
        }
       

        $db = Zend_Registry::get('db');
        $select = $db->select()->from(array('m' => 'menu'), array(
            'id'=>'m.id'
        ));
        $select->joinLeft(array('m1' => 'menu'), 'm1.parent_id = m.id', array())
        ->where('m.parent_id =?',$id)
        ->group('m.id');
        $secondLevelMenuId = $db->fetchAll($select);

        $listId=array();
        foreach ($secondLevelMenuId as $Id){
            $listId[] = $Id['id'];
        }

        $hasChildIDs=array();
        foreach ($listId as $id){
            $select = $db->select();
            $select->from('menu', array('id'));
            $select->where('parent_id=?',$id);
            $result = $db->fetchAll($select);
            if(count($result)>0){
                $hasChildIDs[] = $id;
            }
        }
        $this->view->groupMenu   = $groupMenu;
        $this->view->listId      = $listId;
        $this->view->id          = $id;
        $this->view->staffMenu   = [];
        $this->view->lang        = $lang;
        $this->view->hasChildIDs = $hasChildIDs;        
    }
}



