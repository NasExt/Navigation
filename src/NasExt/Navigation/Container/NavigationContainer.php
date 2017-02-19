<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\Container;

use Nette\SmartObject;

class Container {

	use SmartObject;
	const ADMIN_BASE = 'baseAdmin';
	const FRONT_BASE = 'baseFront';

	/** @var  array */
	private $navigationContainer = array();

	public function __construct() {
		$this->navigationContainer[self::ADMIN_BASE] = new NavigationList();
		$this->navigationContainer[self::FRONT_BASE] = new NavigationList();
	}

	/**
	 * @param string $name
	 * @return NavigationList
	 * @throws \Exception
	 */
	public function createNavigation($name) {
		if (!array_key_exists($name, $this->navigationContainer)) {
			$this->navigationContainer[$name] = new NavigationList();
			return $this->navigationContainer[$name];
		} else {
			throw new \Exception('Navigation with name ".$name." already exist!');
		}
	}

	/**
	 * @param string $name
	 * @return NavigationList
	 * @throws \Exception
	 */
	public function getNavigation($name) {
		if (!array_key_exists($name, $this->navigationContainer)) {
			throw new \Exception('Navigation with name ".$name." not exist!');
		} else {
			return $this->navigationContainer[$name];
		}
	}
}
