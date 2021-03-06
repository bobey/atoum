<?php

namespace mageekguy\atoum\scripts;

use
	mageekguy\atoum,
	mageekguy\atoum\exceptions,
	mageekguy\atoum\scripts\tagger
;

class tagger extends atoum\script
{
	protected $engine = null;
	protected $tagVersion = true;

	public function __construct($name, atoum\locale $locale = null, atoum\adapter $adapter = null)
	{
		$this->setEngine(new tagger\engine($adapter));

		parent::__construct($name, $locale, $this->engine->getAdapter());
	}

	public function setEngine(tagger\engine $engine)
	{
		$this->engine = $engine;

		return $this;
	}

	public function getEngine()
	{
		return $this->engine;
	}

	public function run(array $arguments = array())
	{
		parent::run($arguments);

		if ($this->tagVersion === true)
		{
			$this->engine->tagVersion();
		}

		return $this;
	}

	public function help()
	{
		$this->tagVersion = false;

		return parent::help();
	}

	protected function setArgumentHandlers()
	{
		$engine = $this->engine;

		$this->addArgumentHandler(
			function($script, $argument, $values) {
				if (sizeof($values) != 0)
				{
					throw new exceptions\logic\invalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
				}

				$script->help();
			},
			array('-h', '--help'),
			null,
			$this->locale->_('Display this help')
		);

		$this->addArgumentHandler(
			function($script, $argument, $version) use ($engine) {
				if (sizeof($version) != 1)
				{
					throw new exceptions\logic\invalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
				}

				$engine->setVersion(current($version));
			},
			array('-v', '--version'),
			'<string>',
			$this->locale->_('Use <string> as version value')
		);

		$this->addArgumentHandler(
			function($script, $argument, $versionPattern) use ($engine) {
				if (sizeof($versionPattern) != 1)
				{
					throw new exceptions\logic\invalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
				}

				$engine->setVersionPattern(current($versionPattern));
			},
			array('-vp', '--version-pattern'),
			'<regex>',
			$this->locale->_('Use <regex> to set version in source files')
		);

		$this->addArgumentHandler(
			function($script, $argument, $directory) use ($engine) {
				if (sizeof($directory) != 1)
				{
					throw new exceptions\logic\invalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
				}

				$engine->setSrcDirectory(current($directory));
			},
			array('-s', '--src-directory'),
			'<directory>',
			$this->locale->_('Use <directory> as source directory')
		);

		$this->addArgumentHandler(
			function($script, $argument, $directory) use ($engine) {
				if (sizeof($directory) != 1)
				{
					throw new exceptions\logic\invalidArgument(sprintf($script->getLocale()->_('Bad usage of %s, do php %s --help for more informations'), $argument, $script->getName()));
				}

				$engine->setDestinationDirectory(current($directory));
			},
			array('-d', '--destination-directory'),
			'<directory>',
			 $this->locale->_('Save tagged files in <directory>')
		);

		return $this;
	}
}

?>
