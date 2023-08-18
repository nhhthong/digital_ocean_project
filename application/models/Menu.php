<?php
class Application_Model_Menu extends Zend_Db_Table_Abstract
{
	protected $_name = 'menu';
	function getTitleByUrl($url, $lang){		
		$db = Zend_Registry::get('db');

		if($lang == 2){
			$sql = "SELECT IFNULL(c.id, IFNULL(b.id, a.id)) as id_parent,
				IFNULL(c.title, IFNULL(b.title, a.title)) as title_name,
				IFNULL(c.url, IFNULL(b.url, a.url)) as url_name,
				c.title as title_parent,
				b.title as title_father,
				a.title as title_son,
				c.url as url_parent,
				b.url as url_father,
				a.url as url_son,
				a.parent_id as parent_id,
				a.id as id_son,
				a.hidden as is_hidden
			FROM
				(SELECT * 
				FROM menu 
				where url = '$url') AS a
			LEFT JOIN menu b on a.parent_id = b.id
			LEFT JOIN menu c on b.parent_id = c.id";
		} else {
			$sql = "SELECT IFNULL(c.id, IFNULL(b.id, a.id)) as id_parent,
				IFNULL(c.title_vie, IFNULL(b.title_vie, a.title_vie)) as title_name,
				IFNULL(c.url, IFNULL(b.url, a.url)) as url_name,
				c.title_vie as title_parent,
				b.title_vie as title_father,
				a.title_vie as title_son,
				c.url as url_parent,
				b.url as url_father,
				a.url as url_son,
				a.parent_id as parent_id,
				a.id as id_son,
				a.hidden as is_hidden
			FROM
				(SELECT * 
				FROM menu 
				where url = '$url') AS a
			LEFT JOIN menu b on a.parent_id = b.id
			LEFT JOIN menu c on b.parent_id = c.id";
		}
		$stmt = $db->prepare($sql);                       
		$stmt->execute();
		$title_menu = $stmt->fetchAll();
		$stmt->closeCursor();
		$db = $stmt = null;
		return $title_menu;
	}

	function getMenuByUrlParent($url, $lang, $id_parent){
		
		$db = Zend_Registry::get('db');
		if($lang == 2){
			$sql = "SELECT *
					FROM
					(SELECT IFNULL(c.id, IFNULL(b.id, a.id)) as id_parent,
						IFNULL(c.title, IFNULL(b.title, a.title)) as title_name,
						IFNULL(c.url, IFNULL(b.url, a.url)) as url_name,
						c.title as title_parent,
						b.title as title_father,
						a.title as title_son,
						c.url as url_parent,
						b.url as url_father,
						a.url as url_son,
						a.parent_id as parent_id,
						a.id as id_son,
						a.hidden as is_hidden
					FROM
						(SELECT * 
						FROM menu 
						where url = '$url') AS a
					LEFT JOIN menu b on a.parent_id = b.id
					LEFT JOIN menu c on b.parent_id = c.id) AS tt
					WHERE tt.id_parent = $id_parent";
		} else {
			$sql = "SELECT *
					FROM
					(SELECT IFNULL(c.id, IFNULL(b.id, a.id)) as id_parent,
						IFNULL(c.title_vie, IFNULL(b.title_vie, a.title_vie)) as title_name,
						IFNULL(c.url, IFNULL(b.url, a.url)) as url_name,
						c.title_vie as title_parent,
						b.title_vie as title_father,
						a.title_vie as title_son,
						c.url as url_parent,
						b.url as url_father,
						a.url as url_son,
						a.parent_id as parent_id,
						a.id as id_son,
						a.hidden as is_hidden
					FROM
						(SELECT * 
						FROM menu 
						where url = '$url') AS a
					LEFT JOIN menu b on a.parent_id = b.id
					LEFT JOIN menu c on b.parent_id = c.id) AS tt
					WHERE tt.id_parent = $id_parent";
		}
		$stmt = $db->prepare($sql);                       
		$stmt->execute();
		$title_menu = $stmt->fetchAll();
		$stmt->closeCursor();
		$db = $stmt = null;
		return $title_menu;
	}
}
