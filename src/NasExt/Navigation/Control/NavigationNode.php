<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\Control;

use Nette\ComponentModel\Container;
use Nette\Utils\Strings;

class NavigationNode extends Container {

	/** @var bool */
	public $isCurrent = FALSE;

	/** @var array */
	public $attrs = array();

	/** @var  string */
	private $title;

	/** @var  string */
	private $name;

	/** @var  string|NULL */
	private $link;

	/** @var bool */
	private $isPresenterLink = TRUE;

	/** @var array */
	private $linkArgs = array();

	/** @var  int */
	private $level;

	/** @var string|NULL */
	private $currentLink = NULL;

	/**
	 * @param string            $title
	 * @param string|array|NULL $link
	 * @param string            $currentLink
	 * @param int               $level
	 */
	public function __construct($title, $link = NULL, $currentLink = NULL, $level = 1) {
		parent::__construct();

		$this->title = $title;
		$this->level = $level;
		$this->currentLink = $currentLink;

		// Prepare link
		if (is_array($link)) {
			$this->link = $link[0];
			unset($link[0]);
			$this->linkArgs = $link;
		} else {
			$this->link = $link;
		}

		// If not presenter link
		if (!preg_match('/^:/', $this->link)) {
			$this->isPresenterLink = FALSE;
			if (!empty($this->linkArgs)) {
				$this->link .= '?' . http_build_query($this->linkArgs);
				$this->linkArgs = array();
			}
		}

		// Prepare name
		if ($this->link !== NULL) {
			$name = $this->link;
		} else {
			$name = $this->title;
		}
		$this->name = $this->normalizeName($name);
	}

	/**
	 * @param string $name
	 * @return string
	 */
	private function normalizeName($name) {
		return str_replace('-', '_', Strings::webalize($name));
	}

	/**
	 * @return NavigationNode
	 */
	public function setCurrent() {
		$this->isCurrent = TRUE;
		$this->lookup(NavigationControl::class)->setCurrentNode($this);

		return $this;
	}

	/**
	 * @param string      $title
	 * @param string|NULL $link
	 * @return NavigationNode
	 */
	public function add($title, $link = NULL) {
		$node = new self($title, $link, $this->currentLink, $this->level + 1);
		$this->addComponent($node, $node->getName());

		if ($this->currentLink != NULL && $this->currentLink == $node->getLink()) {
			$node->setCurrent();
		}
		return $node;
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
	 * @param bool $string
	 * @return array|string
	 */
	public function getAttributes($string = TRUE) {
		$attributes = $this->attrs;
		if ($string) {
			return join(
				' ', array_map(
					   function ($key) use ($attributes) {
						   return $key . '="' . htmlspecialchars($attributes[$key]) . '"';
					   }, array_keys($attributes)
				   )
			);
		}

		return $attributes;
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

	/**
	 * @return bool
	 */
	public function hashChild() {
		return count($this->getComponents()) > 0 ? TRUE : FALSE;
	}

	/**
	 * @return Container
	 */
	public function getChild() {
		return $this->getComponents();
	}

	/**
	 * @return Container
	 */
	public function isCurrent() {
		return $this->isCurrent;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return string
	 */
	public function getLink() {
		return $this->link;
	}

	/**
	 * @return array
	 */
	public function getLinkArgs() {
		return $this->linkArgs;
	}

	/**
	 * @return int
	 */
	public function getLevel() {
		return $this->level;
	}

	/**
	 * @return bool
	 */
	public function isPresenterLink() {
		return $this->isPresenterLink;
	}
}
