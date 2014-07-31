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


		
class ModelPartidos extends JModel{
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
		$tbMatch = $db->nameQuote('#__zmatchs');
		$tbTeam = $db->nameQuote('#__zteams');
		
		$query = "SELECT 
						matchs.id,
						team1.name as team1Name, 
						team2.name as team2Name, 
						matchs.goals_team1 as result_team1, 
						matchs.goals_team2 as result_team2, 
						matchs.matchdate, 
						matchs.tgroup,
						procesed
				  FROM 
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND
						(
							team1.name like '%s' OR
							team2.name like '%s' 
						)
				  ORDER BY
						matchs.matchdate
						";
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query, $filtro, $filtro );
		//echo $query;
		$db->setQuery($query, $inicio, $registros);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	function contar($filtro){
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbMatch = $db->nameQuote('#__zmatchs');
		$tbTeam = $db->nameQuote('#__zteams');
		
		$query = "SELECT 
						count(*)
				  FROM 
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND
						(
							team1.name like '%s' OR
							team2.name like '%s' 
						)
						";
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query,  $filtro, $filtro );
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
						team1.name as team1Name, 
						team2.name as team2Name, 
						matchs.goals_team1 as result_team1, 
						matchs.goals_team2 as result_team2, 
						matchs.matchdate, 
						matchs.tgroup
						procesed
				  FROM 
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND
						(
							team1.name like '%s' OR
							team2.name like '%s' 
						)						
				  ORDER BY
						matchs.matchdate
						";
		$filtro = "%". $filtro . "%";
		$query = sprintf( $query,  $filtro, $filtro );
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result;
	}
	
	
	/**
	* Lista de usuarios
	*/
	function getPartido($id){	
		$db = Configuration::getConcursoDB();
		//$db = & JFactory::getDBO();
		$tbMatch = $db->nameQuote('#__zmatchs');
		$tbTeam = $db->nameQuote('#__zteams');
		
		$query = "SELECT 
						matchs.id,
						team1.name as team1Name, 
						team2.name as team2Name
				  FROM 
						$tbMatch as matchs,
						$tbTeam as team1,
						$tbTeam as team2
				  WHERE 
						matchs.team1 = team1.id AND
						matchs.team2 = team2.id AND
						matchs.id = %s
						";
		$query = sprintf( $query, $id );
		//echo $query;
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		return $result[0];
	}
	
	function asignarPuntos($match , $resultTeam1 , $resultTeam2){
		$db = Configuration::getConcursoDB();
		
		$tablePredicts = $db->nameQuote('#__zpredicts');
		//Get all the predictions of the match ( avoid the admin )
		$query = "  SELECT 
	      				*
	      			FROM  
	      				 $tablePredicts as zpredicts
	                WHERE
	                	tmatch = $match AND
					    user != 62
	                ";
	    
	    //echo $query;
	    $db->setQuery( $query );
	    $predictions = $db->loadObjectList();
	    //print_r( $predictions );
	    
	    //Init a transaction
	    ZDBHelper::initTransaction($db);
	    
	    //Review each prediction and assign points
	    foreach( $predictions as $prediction ){
	    
	    	//print_r( $prediction );
	    	$id = $prediction->id;
	    	$goals1 = $prediction->goals_team1;
	    	$goals2 = $prediction->goals_team2;
	    	//echo "id = $id goals1 = $goals1 goals2 = $goals2 ";
	    	
	    	$points = 0 ;
	    	$pointsExact = 0;
	    	$pointsResult = 0;
			$pointsNoOrder = 0;
	    	
	    	if( $goals1 == $resultTeam1 && $goals2 ==  $resultTeam2 ){
	    		$pointsExact = 5;
	    	}
	    	else if(
	    			 ( $resultTeam1 >  $resultTeam2  && $goals1 > $goals2 ) ||
	    			 ( $resultTeam1 <  $resultTeam2  && $goals1 < $goals2 ) ||
	    			 ( $resultTeam1 ==  $resultTeam2  && $goals1 == $goals2 ) 
	    			)
	    	{
	    			$pointsResult =  3; 
	    	}
			else if( $goals1 == $resultTeam2 && $goals2 ==  $resultTeam1 ){
				$pointsNoOrder =  1; 
			}
	    	
	    	
	    	//Asign points to the pronostic of the user
	    	$result = $this->assignPointsPronostic( $id , $pointsExact + $pointsResult + $pointsNoOrder  );
	    	if( $result == FALSE ){
	    		//rollback
	    		//echo "in pronostic";
	    		ZDBHelper::rollback( $db );
	    		return false;
	    	}
	    	
	    	//Asign points to the user
	    	$result = $this->assignPointsUser( $prediction->user , $pointsExact , $pointsResult, $pointsNoOrder  );
	    	if( $result == FALSE ){
	    		//rollback
	    		//echo "in user";
	    		ZDBHelper::rollback( $db );
	    		return false;
	    	}
	    	
	    	
	    } 
	    
	    //Change the procesed to 1
	    $result = $this->changeMatchState( $match , 1 , $resultTeam1 , $resultTeam2 );
	    //echo "result_e =" . $result ;
		if( $result == FALSE  ){
				//echo "in match";
	    		ZDBHelper::rollback(  $db  );
	    		return false;
	    }
	   
	    ZDBHelper::commit( $db );
	    return $result;
	}
	
	/**
	 * Assign points to a specific pronostic
	 */
	function assignPointsPronostic( $pronostic , $points  ){
		$db = Configuration::getConcursoDB();
		
		$tablePredicts = $db->nameQuote('#__zpredicts');
		
		
		$query = " UPDATE
	      				$tablePredicts as zpredicts
	      		   SET
	      		   	    points_earned = $points
	                WHERE
	                	id = $pronostic
	                ";
	      				
	 	//echo $query;
	    $db->setQuery( $query );
		$result = $db->query(  );
		return $result ;
	}
	
	/**
	 * Assign points to a user
	 */
	function assignPointsUser( $user , $pointsExact , $pointsResult, $pointsNoOrder ){
		$db = Configuration::getConcursoDB();
		
		$tableUsers = $db->nameQuote('#__users');
		
		
		$query = " UPDATE
	      				$tableUsers as users
	      		   SET
	      		   	    points = points + $pointsExact + $pointsResult + $pointsNoOrder,
	      		   	    points_exact = points_exact +  $pointsExact ,
	      		   	    points_result = points_result +  $pointsResult,
						points_norder = points_norder +  $pointsNoOrder
	                WHERE
	                	id = $user
	                ";
	      				
	 	//echo $query;
	    $db->setQuery( $query );
		$result = $db->query(  );
		return $result;
	}
	
	
	/**
	 * Update a match
	 */
	function changeMatchState( $match , $procesed , $goalsTeam1 , $goalsTeam2  ){
		$db = Configuration::getConcursoDB();
		$tableMatchs = $db->nameQuote('#__zmatchs');
		//Get all the predictions of the match ( avoid the admin )
		$query = " UPDATE
	      				$tableMatchs as zmatchs
	      		   SET
	      		   	    procesed = $procesed,
	      		   	    goals_team1 = $goalsTeam1,
	      		   	    goals_team2 = $goalsTeam2
	                WHERE
	                	id = $match
	                ";
	      				
	 	//echo $query;
	    $db->setQuery( $query );
		$result = $db->query( );
		return $result;
		//return $db->getAffectedRows();
	}
	
}









