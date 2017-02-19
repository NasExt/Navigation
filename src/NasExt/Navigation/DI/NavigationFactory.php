<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\DI;

use NasExt\Navigation\Container\NavigationContainer;
use Nette\Iterators\Mapper;
use Nette\Object;

class NavigationFactory extends Object {

	/** @var INavigation[] */
	protected $navigationResolver;

	/**
	 * @param Mapper $navigationResolver
	 */
	public function __construct(Mapper $navigationResolver) {
		$this->navigationResolver = $navigationResolver;;
	}

	/**
	 * @return NavigationContainer
	 */
	public function create() {
		$navigationContainer = new NavigationContainer();

		foreach ($this->navigationResolver as $item) {
			$item->setNavigation($navigationContainer);
		}

		return $navigationContainer;
	}
}


