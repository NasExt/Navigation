<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\Control;

use NasExt\Navigation\Container\NavigationList;
use Nette\Application\UI\Control;

class NavigationControl extends Control {

	/** @var  string */
	public $templateFile;

	/** @var string|NULL */
	private $currentLink = FALSE;

	/** @var NavigationNode */
	private $current;

	/**
	 * @param string|NULL $currentLink
	 */
	public function __construct($currentLink = NULL) {
		parent::__construct();

		$reflection = $this->getReflection();
		$dir = dirname($reflection->getFileName());
		$name = $reflection->getShortName();
		$this->templateFile = $dir . DIRECTORY_SEPARATOR . $name . '.latte';

		$this->currentLink = $currentLink;
	}

	/**
	 * @param string      $title
	 * @param string|NULL $link
	 * @return NavigationNode
	 */
	public function add($title, $link = NULL) {
		$node = new NavigationNode($title, $link, $this->currentLink);
		$this->addComponent($node, $node->getName());

		if ($this->currentLink != NULL && $this->currentLink == $node->getLink()) {
			$this->setCurrentNode($node);
		}

		return $node;
	}

	/**
	 * Set node as current
	 * @param NavigationNode $node
	 */
	public function setCurrentNode(NavigationNode $node) {
		if (isset($this->current)) {
			$this->current->isCurrent = FALSE;
		}
		$node->isCurrent = TRUE;
		$this->current = $node;
	}

	/**
	 * @return NavigationNode
	 */
	public function getCurrentNode() {
		return $this->current;
	}

	/**
	 * @param NULL|string $currentLink
	 */
	public function setCurrentLink($currentLink = NULL) {
		$this->currentLink = $currentLink;
	}

	/**
	 * @return NavigationNode[]
	 */
	public function getParentsOfCurrentNode() {
		$parents = array();
		if (isset($this->current)) {
			$parent = $this->current->getParent();
			while ($parent instanceof NavigationNode) {
				$parents[$parent->getName()] = $parent;
				$parent = $parent->getParent();
			}
		}

		return $parents;
	}

	/**
	 * Helper for automatic add items from NasExt\NavigationContainer extension
	 * @param NavigationList      $menu
	 * @param bool|NavigationNode $parent
	 */
	public function addFromContainer(NavigationList $menu, &$parent = FALSE) {
		foreach ($menu->getItems() as $item) {
			if ($parent == FALSE) {
				$node = $this->add($item->getTitle(), $item->getLink());
			} else {
				$node = $parent->add($item->getTitle(), $item->getLink());
			}

			if ($item->hasChildren()) {
				$this->addFromContainer($item->getChildren(), $node);
			}
		}
	}

	public function render() {
		$this->template->navigation = $this->getComponents();
		$this->template->setFile($this->templateFile);
		$this->template->parentsOfCurrentNode = $this->getParentsOfCurrentNode();
		$this->template->render();
	}
}
