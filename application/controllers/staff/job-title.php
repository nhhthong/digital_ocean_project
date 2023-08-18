<?php
$QTeam                      = new Application_Model_Team();
$whereDepartment[]          = $QTeam->getAdapter()->quoteInto('parent_id = ?', 0);
$whereDepartment[]          = $QTeam->getAdapter()->quoteInto('del = ? OR del IS NULL', 0);
$listDepartment             = $QTeam->fetchAll($whereDepartment);
$this->view->listDepartment = $listDepartment;