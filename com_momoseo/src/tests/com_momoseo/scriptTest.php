<?php
require_once ‘PHPUnit/Framework.php’;
//inclui o framework do phpunit

require_once diname(__FILE__).'../framework.php';
require_once diname(__FILE__).'../../com_momoseo/script.php';

// indica o arquivo de classe a ser testado
class CalcClassTest extends PHPUnit_Framework_TestCase
{
	protected $object;
	protected function setup()
	{
		$this->object = new CalcClass;
	}

	public function testSomar()
	{
		$test=new CalcClass();
		$this->assertEquals(‘4’, $test->somar(2,2));
		// verifica se é igual
	}

	public function testSubtrair()
	{
		$test=new CalcClass();
		$this->assertGreaterThan(1, $test->subtrair(10,1));
		//verifica se é maior que
	}
}
?>