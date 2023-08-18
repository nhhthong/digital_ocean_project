<?php
/**
 * Class for generating nested lists
 *
 * example:
 *
 * $tree = new Tree;
 * $tree->add_row(1, 0, '', 'Menu 1');
 * $tree->add_row(2, 0, '', 'Menu 2');
 * $tree->add_row(3, 1, '', 'Menu 1.1');
 * $tree->add_row(4, 1, '', 'Menu 1.2');
 * echo $tree->generate_list();
 *
 * output:
 * <ul>
 * 	<li>Menu 1</li>
 * 	<li>Menu 2</li>
 * </ul>
 *
 * @author gawibowo
 */
class TreeParent3 {

	/**
	 * variable to store temporary data to be processed later
	 *
	 * @var array
	 */
	var $data;

	/**
	 * Add an item
	 *
	 * @param int $id 			ID of the item
	 * @param int $parent 		parent ID of the item
	 * @param string $li_attr 	attributes for <li>
	 * @param string $label		text inside <li></li>
	 */
	function add_row($id, $parent, $li_attr, $label) {
		$this->data[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
	}

	/**
	 * Generates nested lists
	 *
	 * @param string $ul_attr
	 * @return string
	 */
	function generate_list($ul_attr = '', $display, $style) {
		return $this->ul(0, $ul_attr, $display, $style);
	}

	/**
	 * Recursive method for generating nested lists
	 *
	 * @param int $parent
	 * @param string $attr
	 * @return string
	 */
	function ul($parent = 0, $attr = '', $display = '', $style = '') {
		static $i = 1;
		$indent = str_repeat("\t\t", $i);
		if (isset($this->data[$parent])) {
			if ($attr) {
				$attr = ' ' . $attr;
			}

			$class = 'class = "menu-nav menu-nav3 '.$display.'" id="menu-nav3" '.$style;
			if ($parent > 0)
				$class = ' class="level2"';

			$html = "\n$indent";
			$html .= "<ul$attr $class>";
			$i++;
			foreach ($this->data[$parent] as $row) {
				$child = $this->ul($row['id']);
				$html .= "\n\t$indent";
				$html .= '<li class="menu-item"'. $row['li_attr'] . '>';
				$html .= $row['label'];
				$html .= '</li>';
			}
			$html .= "\n$indent</ul>";
			return $html;
		} else {
			return false;
		}
	}

	/**
	 * Clear the temporary data
	 *
	 */
	function clear() {
		$this->data = array();
	}
}