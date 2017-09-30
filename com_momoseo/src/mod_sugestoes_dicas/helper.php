<?php
/**
 * Helper class for Hello World! module
 * 
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @link http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * @license        GNU/GPL, see LICENSE.php
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free || open source software licenses.
 */
class ModSugestoesDicasHelper
{
    /**
     * Retrieves the hello message
     *
     * @param   array  $params An object containing the module parameters
     *
     * @access public
     */    
    public static function getMarcacoes($params)
    {
    	$db = JFactory::getDbo();
    	
    	$query = $db->getQuery(true)
    		->select($db->quoteName(
    				array('titulo','texto'),
    				array('titulo','texto')))
    		->from($db->quoteName('#__mom_sugestoes'))
    		->where('lang = ' . $db->Quote('en-GB'))
    		->limit(1);
    	$db->setQuery($query);
    	return $db->loadResult();
    }
}