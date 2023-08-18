<?php
class AjaxController extends My_Controller_Action { 
    public function loadTeamAction() {
        $QTeam   = new Application_Model_Team();
        $department_id = $this->getRequest()->getParam('department_id');        

        if (is_array($department_id) && count($department_id) > 0)
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id IN (?)', $department_id);
        elseif (is_numeric($department_id)) {
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $department_id);
        } else {
            $where[] = $QTeam->getAdapter()->quoteInto('1 = 0', 1);
        }

        $where[] = $QTeam->getAdapter()->quoteInto('is_hidden = ?', 0);
        $where[] = $QTeam->getAdapter()->quoteInto('del = ?', 0);
        echo json_encode($QTeam->fetchAll($where, 'name')->toArray());
        exit;
    }

    public function loadTitleAction() {
        $QTeam   = new Application_Model_Team();
        $team_id = $this->getRequest()->getParam('team_id');        
        $where   = array();
        
        if (is_array($team_id) && count($team_id) > 0)
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id IN (?)', $team_id);
        elseif (is_numeric($team_id)) {
            $where[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $team_id);
        } else {
            $where[] = $QTeam->getAdapter()->quoteInto('1=0', 1);
        }

        $where[] = $QTeam->getAdapter()->quoteInto('del = ?', 0);
        $where[] = $QTeam->getAdapter()->quoteInto('is_hidden = ?', 0);
        echo json_encode($QTeam->fetchAll($where, 'name')->toArray());
        exit;
    }

    public function getTeamAction () {
        $departmentID = $this->getRequest()->getParam('department_id');
        $QTeam = new Application_Model_Team();
        $whereTeam = array();
        $whereTeam[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $departmentID);
        $whereTeam[] = $QTeam->getAdapter()->quoteInto('del = ? OR del IS NULL', 0);
        $Teams = $QTeam->fetchAll($whereTeam);
        if ($Teams) {
            echo json_encode($Teams->toArray());
        }
        exit;
    }

    
    public function getJobTitleAction() {
        $teamID = $this->getRequest()->getParam('team_id');
        $QTeam = new Application_Model_Team();
        $whereJobTile = array();
        $whereJobTile[] = $QTeam->getAdapter()->quoteInto('parent_id = ?', $teamID);
        $whereJobTile[] = $QTeam->getAdapter()->quoteInto('del = ? OR del IS NULL', 0);
        $jobTitle = $QTeam->fetchAll($whereJobTile);
        if ($jobTitle) {
            echo json_encode($jobTitle->toArray());
        }
        exit;
    }
}