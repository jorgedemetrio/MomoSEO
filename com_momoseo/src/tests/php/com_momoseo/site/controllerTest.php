<?php
#require_once ‘PHPUnit/Framework.php’;
//inclui o framework do phpunit

require_once dirname(__FILE__).'/../../../main/php/com_momoseo/script.php';
define('JPATH_COMPONENT',JOOMLA_ADMIN_PATH.'/components/com_momoseo');


// indica o arquivo de classe a ser testado
class MomoseoControllerTest extends PHPUnit_Framework_TestCase
{
	protected $object;
	protected function setup()
	{
		$this->object = new MomoseoController();
	}
	
}