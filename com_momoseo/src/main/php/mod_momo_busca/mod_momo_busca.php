<?php
/**
 * Hello World! Module Entry Point
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free || open source software licenses.
 */

// No direct access
defined('_JEXEC') || die;

$cx = $params->get('cx', 'partner-pub-2442632731348664:3010243382');
$lblBotaoBusca = $params->get('lblBotaoBusca', 'Busca');
$lblImagem = $params->get('lblImagem');
$lblClass = $params->get('lblClass', '');
$cof = $params->get('cof', 'FORID:10');


