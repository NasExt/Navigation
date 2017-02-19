<?php
/**
 * This file is part of the NasExt extensions of Nette Framework
 * Copyright (c) 2013 Dusan Hudak (http://dusan-hudak.com)
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace NasExt\Navigation\DI;

use Arachne\ServiceCollections\DI\ServiceCollectionsExtension;
use NasExt\Navigation\Control\INavigationControlFactory;
use NasExt\Navigation\Control\NavigationControl;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\Utils\AssertionException;

class NavigationExtension extends CompilerExtension {

	const NAVIGATION_TAG = 'navigation.tag';

	public function loadConfiguration() {
		$builder = $this->getContainerBuilder();

		// NavigationControl
		$builder->addDefinition($this->prefix('navigationControl'))
			->setImplement(INavigationControlFactory::class)
			->setFactory(NavigationControl::class)
			->setArguments(array($builder->literal('$currentLink')))
			->setParameters(array('currentLink' => NULL));

		/* @var $serviceCollectionsExtension ServiceCollectionsExtension */
		$serviceCollectionsExtension = $this->getExtension(ServiceCollectionsExtension::class);

		$navigationResolver = $serviceCollectionsExtension->getCollection(
			ServiceCollectionsExtension::TYPE_ITERATOR,
			self::NAVIGATION_TAG,
			INavigation::class
		);

		// NavigationContainer
		$builder->addDefinition($this->prefix('navigation'))
			->setFactory(array(new Statement(NavigationFactory::class, array('navigationResolver' => '@' . $navigationResolver)), 'create'));
	}

	/**
	 * @param   string $class
	 * @param bool     $need
	 * @return CompilerExtension|null
	 * @throws AssertionException
	 */
	private function getExtension($class, $need = TRUE) {
		$extensions = $this->compiler->getExtensions($class);
		if (!$extensions) {
			if (!$need) {
				return NULL;
			}
			throw new AssertionException(
				sprintf('Extension "%s" requires "%s" to be installed.', get_class($this), $class)
			);
		}
		return reset($extensions);
	}
}
