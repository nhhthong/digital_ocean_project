<?php

class My_Controller_Action extends Zend_Controller_Action {

    public function preDispatch() {        
        $userStorage = Zend_Auth::getInstance()->getStorage()->read();
        if ($userStorage) {
            $controller                  = $this->getRequest()->getControllerName();
            $action                      = $this->getRequest()->getActionName();
            $this->view->controller_name = $controller;
            $this->view->action_name     = $action;
            $actual_link                 = My_Util::escape_string($_SERVER['REQUEST_URI']);
            if($_COOKIE['defaut_lang']) {
                $portal_lang = $_COOKIE['defaut_lang'];
                if($portal_lang !== $userStorage->defaut_language) {
                    $userStorage->defaut_language = $portal_lang;
                }
            }
            $lang = $userStorage->defaut_language;
            $this->view->lang = $lang;
            $QTeam            = new Application_Model_Team();
            $staff_title_info = $userStorage->title;                    
            $team_info        = $QTeam->fetchRow(
                [
                    $QTeam->getAdapter()->quoteInto ('id = ?', $staff_title_info)
                ]
            );
            $group_id         = $team_info->access_group;
           
            if (isset($userStorage->menu) && $userStorage->menu) {
                $menu = $userStorage->menu;
            } else {
                $QMenu = new Application_Model_Menu();
                $where = $QMenu->getAdapter()->quoteInto('group_id = ?', $group_id);
                $menu  = $QMenu->fetchAll($where, array('parent_id', 'position'));
            }
            
            $array_menu_parent = array();
            foreach ($menu as $row) {
                if($row['parent_id'] == 0){
                    array_push($array_menu_parent, $row);
                }
            }
            $this->view->total_number_menu = count($array_menu_parent);
    
            $QMenu                  = new Application_Model_Menu();
            $action_name            = Zend_Controller_Front::getInstance()->getRequest()->getActionName() == 'index' ? '' : '/' . Zend_Controller_Front::getInstance()->getRequest()->getActionName();
            $controller_action_name = '/' . Zend_Controller_Front::getInstance()->getRequest()->getControllerName() . $action_name;
    
            if($controller_action_name == '/view-menu'){
                $title_menu = $QMenu->getTitleByUrl($actual_link, $lang);
            } else {
                $title_menu = $QMenu->getTitleByUrl($controller_action_name, $lang);
                if($title_menu[0]['is_hidden'] == '1') {
                    $parent_ca_name = $title_menu[0]['url_father'];
                    $title_menu     = $QMenu->getTitleByUrl($parent_ca_name, $lang);
                }
            }
    
            if(mb_strlen($title_menu[0]["title_name"]) > 9) {
                $title_menu_mobile  = mb_substr($title_menu[0]["title_name"], 0, 9, 'UTF-8');
                $title_menu_mobile .= ' ...';
            } else {
                $title_menu_mobile  = $title_menu[0]["title_name"];
            }
            $this->view->title_menu = $title_menu_mobile;
            
            if($title_menu) {
                if($_SESSION["breadcrumb"]) {
                    unset($_SESSION['breadcrumb']);
                }
                $_SESSION["breadcrumb"] = $title_menu[0];
            }
            $this->view->breadcrumb = $_SESSION["breadcrumb"];
    
            $list_lang = My_Lang::getLangList();
            if (!empty($userStorage) && isset($userStorage->defaut_language)) {
                $user_lang    = $userStorage->defaut_language;
                $current_lang = $list_lang[$user_lang];
            } else {
                $current_lang = 'vi';
            }
            $this->view->current_lang      = $current_lang;
            $this->view->translate_adapter = Zend_Registry::get('translate');
            $change_color = $this->getRequest()->getParam('btn-change-mode');
            $cookie_name  = "layout_mode";
            if($change_color == "1") {
                if($_COOKIE['layout_mode']){
                    unset($_COOKIE['layout_mode']);
                    setcookie($cookie_name, null, -1, '/', COOKIE_LAYOUT); 
                }
                $cookie_value = "1";
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/", COOKIE_LAYOUT); // 86400 = 1 day
                $this->redirect(HOST);
            } else if($change_color == "2") {
                if($_COOKIE['layout_mode']){
                    unset($_COOKIE['layout_mode']);
                    setcookie($cookie_name, null, -1, '/', COOKIE_LAYOUT); 
                }
                $cookie_value = "2";
                setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/", COOKIE_LAYOUT); // 86400 = 1 day
                $this->redirect(HOST);
            }
    
            if(isset($_COOKIE['layout_mode']) && $_COOKIE['layout_mode'] == "1"){
                $_SESSION["light_template"] = 1;
            } else if(isset($_COOKIE['layout_mode']) && $_COOKIE['layout_mode'] == "2"){
                unset($_SESSION['light_template']);
            }
            $is_layout = $this->_helper->layout->getLayout();
            if ($is_layout == 'layout') {
                $this->_helper->layout->setLayout('layout_metronic');
            } else {
                die ("...");
            }
        }        
    }   
        
    public static function distance($lat1, $lon1, $lat2, $lon2) {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lon1 *= $pi80;
        $lat2 *= $pi80;
        $lon2 *= $pi80;
        $r    = 6372.797; 
        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;
        $a    = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
        $c    = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km   = $r * $c;
        return round($km * 1000, 1);
    }

    

    public function rule_penalti($time) {
        if ($time <= 120) {
            $x = 0;
        } elseif ($time > 120 && $time <= 180) {
            $x = 0.5;
        } else {
            $phat   = round(($time * 2) / 480, 2);
            $nguyen = floor($phat);
            $x      = 0;
            $phat   = round($phat - $nguyen,2);
            if ($phat >= 0 && $phat <= 0.26) {
                $x = $nguyen;
            }
            if ($phat >= 0.27 && $phat <= 0.75) {
                $x = $nguyen + 0.5;
            }
            if ($phat >= 0.76 && $phat <= 1) {
                $x = $nguyen + 1;
            }
        }
    
        return $x;
    }

    public static function insertAllrowDB($params, $db, $db_log, $schema = 'hr') {
        try {
            $temp = $params[0];
            $str_insert = '';
            foreach ($params as $param) {
                $param_data = [];
                foreach ($param as $key => $value){
                    $param_data[$key] = str_replace("'", "\'", $value);
                }
                $str_insert .= "('" . implode("', '", $param_data) . "')" . ',';            
            }
            $str_rows = rtrim($str_insert, ',');            
            $sql = "INSERT INTO $schema.`$db` ";
            $sql .= " (`" . implode("`, `", array_keys($temp)) . "`)";            
            $sql .= " VALUES $str_rows ";            
            $db_log->query($sql);                        
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }
}
