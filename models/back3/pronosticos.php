<?php
/**
 * User Pronosticos
 *
 * @version $Id:  
 * @author Andres Quintero
 * @package Joomla
 * @subpackage zschool
 * @license GNU/GPL
 *
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

/**
 *
 * @author      aquintero
 * @package		Joomla
 * @since 1.6
 */


		
class ModelPronosticos extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	/**
	* Lista de usuarios
	*/
	function listar($filtro, $inicio, $registros){	
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbPredicts = $db->nameQuote('#__zpredicts');
		$tbUsers = $db->nameQuote('#__users');
		$tbMatch = $db->nameQuote('#__zmatchs');
		$tbTeam = $db->nameQuote('#__zteams');
		
		$query = "SELECT 
						users.username, 
						team1.name as team1, 
						team2.name as team2, 
						matchs.goals_team1 as result_team1, 
						matchs.goals_team2 as result_team2, 
						procesed,
						predicts.goals_team1,
						predicts.goals_team2,
						predicts.last_updated,
						predicts.points_earned
				  FROM 
						$tbPredicts as predicts,
						$tbUsers as users,
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						predicts.user = users.id AND
						predicts.tmatch = matchs.id AND
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND 
						username like '%s'
						
				  ORDER BY
						predicts.id DESC
						";
		$query = sprintf( $query, "%". $filtro . "%" );
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro){
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbPredicts = $db->nameQuote('#__zpredicts');
		$tbUsers = $db->nameQuote('#__users');
		$tbMatch = $db->nameQuote('#__zmatchs');
		$tbTeam = $db->nameQuote('#__zteams');
		
		$query = "SELECT 
						count(*)
				 FROM 
						$tbPredicts as predicts,
						$tbUsers as users,
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						predicts.user = users.id AND
						predicts.tmatch = matchs.id AND
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND
						username like '%s'
						";
		
		$query = sprintf( $query, "%". $filtro . "%" );
		$db->setQuery($query);
	    $result = $db->loadResult();
		return $result;
	}
	
	/**
	* Lista de usuarios
	*/
	function listarTodos($filtro){	
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbPredicts = $db->nameQuote('#__zpredicts');
		$tbUsers = $db->nameQuote('#__users');
		$tbMatch = $db->nameQuote('#__zmatchs');
		$tbTeam = $db->nameQuote('#__zteams');
		
		$query = "SELECT 
						users.username, 
						team1.name as team1, 
						team2.name as team2, 
						matchs.goals_team1 as result_team1, 
						matchs.goals_team2 as result_team2, 
						procesed,
						predicts.goals_team1,
						predicts.goals_team2,
						predicts.last_updated,
						predicts.points_earned
				  FROM 
						$tbPredicts as predicts,
						$tbUsers as users,
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						predicts.user = users.id AND
						predicts.tmatch = matchs.id AND
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND 
						username like '%s'
						
				  ORDER BY
						predicts.id DESC
						";
		$query = sprintf( $query, "%". $filtro . "%");
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	
}









