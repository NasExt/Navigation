<?php
/**
 * This file is part of the Nas of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\DI;

use NasExt\Navigation\Container\NavigationContainer;

interface INavigation {

	/**
	 * @param NavigationContainer $navigationContainer
	 * @return void
	 */
	public function setNavigation(NavigationContainer $navigationContainer);
}
