<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\Container;

class NavigationItem {

	/** @var array */
	public $attrs = array();

	/** @var  string */
	private $title;

	/** @var  string|array|NULL */
	private $link;

	/** @var NavigationList */
	private $childItems;

	/**
	 * @param string            $title
	 * @param string|array|NULL $link
	 */
	public function __construct($title, $link = NULL) {
		$this->title = $title;
		$this->link = $link;
		$this->childItems = new NavigationList();
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return null|string
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * @param string            $title
	 * @param string|array|NULL $link
	 * @return NavigationItem
	 */
	public function addChild($title, $link = NULL) {
		return $this->childItems->addItem($title, $link);
	}

	/**
	 * @return bool
	 */
	public function hasChildren() {
		if (!empty($this->childItems->getItems())) {
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * @return NavigationList
	 */
	public function getChildren() {
		return $this->childItems;
	}

	/**
	 * @param array $attrs
	 * @return $this
	 */
	public function addAttributes(array $attrs) {
		$this->attrs = array_merge($this->attrs, $attrs);
		return $this;
	}

	/**
	 * @return array
	 */
	public function getAttributes() {
		return $this->attrs;
	}

	/**
	 * @param string $name
	 * @return null|mixed
	 */
	public function getAttribute($name) {
		return isset($this->attrs[$name]) ? $this->attrs[$name] : NULL;
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 * @return $this
	 */
	public function setAttribute($name, $value) {
		$this->attrs[$name] = $value;
		return $this;
	}
}
