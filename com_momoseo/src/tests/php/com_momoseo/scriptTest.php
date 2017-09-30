<?php
require_once ‘PHPUnit/Framework.php’;
//inclui o framework do phpunit

require_once diname(__FILE__).'../../../com_momoseo/script.php';

// indica o arquivo de classe a ser testado
class com_momoseoInstallerScriptTest extends PHPUnit_Framework_TestCase
{
	protected $object;
	protected function setup()
	{
		$this->object = new com_momoseoInstallerScript;
	}

	/**
	 * method to install the component
	 *
	 *
	 * @return void
	 */
	function installTest($parent)
	{
		$this->object->install('a');
	}
	
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function uninstallTest($parent)
	{
		$this->object->uninstall('a');
	}
	
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function updateTest($parent)
	{
		$this->object->update('a');
	}
	
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function preflightTest($type, $parent)
	{
		$this->object->preflight('a');
	}
	
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function postflightTest($type, $parent)
	{
		$this->object->postflight('a');
	}
}
?>