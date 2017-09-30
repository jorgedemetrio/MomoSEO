<?php
#require_once ‘PHPUnit/Framework.php’;
//inclui o framework do phpunit

require_once dirname(__FILE__).'/../../../main/php/com_momoseo/script.php';

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
	function testInstall()
	{
		$this->object->install('a');
	}
	
	/**
	 * method to uninstall the component
	 *
	 * @return void
	 */
	function testUninstall()
	{
		$this->object->uninstall('a');
	}
	
	/**
	 * method to update the component
	 *
	 * @return void
	 */
	function testUpdate()
	{
		$this->object->update('a');
	}
	
	/**
	 * method to run before an install/update/uninstall method
	 *
	 * @return void
	 */
	function testPreflight()
	{
		$this->object->preflight('a','b');
	}
	
	/**
	 * method to run after an install/update/uninstall method
	 *
	 * @return void
	 */
	function testPostflight()
	{
		$this->object->postflight('a','b');
	}
}
?>