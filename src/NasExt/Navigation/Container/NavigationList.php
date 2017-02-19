<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\Container;

class NavigationList {

	/** @var  NavigationItem[] */
	private $items = array();

	/**
	 * @param string      $title
	 * @param string|array|NULL $link
	 * @return NavigationItem
	 */
	public function addItem($title, $link = NULL) {
		$item = new NavigationItem($title, $link);
		$this->items[] = $item;
		return $item;
	}

	/**
	 * @return NavigationItem[]
	 */
	public function getItems() {
		return $this->items;
	}
}
