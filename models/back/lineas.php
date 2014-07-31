<?php
/**
 * User Model
 *
 * @version $Id:  
 * @author Andres Quintero
 * @package Joomla
 * @subpackage zschool
 * @license GNU/GPL
 *
 * Allows to manage user data
 *
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

//require_once( JPATH_COMPONENT . DS .'models' . DS . 'zteam.php' );


/**
 * ZTelecliente
 *
 * @author      aquintero
 * @package		Joomla
 * @subpackage	ztelecliente
 * @since 1.6
 */


		
class ModelLineas extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	
	function getLineas($usuario,  $filtro){
		$db = & JFactory::getDBO();
		
		$tbIPCentrex 	= $db->nameQuote('#__zipcentrex');
		$tbLineas 		= $db->nameQuote('#__zlineas');
		
		
		$query = "
					SELECT 
						lineas.*
					FROM 
						$tbIPCentrex as ipcentrex,
						$tbLineas as lineas
					WHERE
						ipcentrex.usuario = $usuario AND
						lineas.group_id = ipcentrex.group_id AND
						(
							lineas.linea like '%$filtro%' OR 
							lineas.ext like '%$filtro%' OR
							lineas.direccion like '%$filtro%' 
						)
						";
						
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		foreach($result as $linea){
			//Obtiene categorias
			$msg = "";
			$tooltip = "";
			$this->mensajeCategorias($linea, $msg, $tooltip);
			$linea->mensajeCategoria = $msg;
			$linea->tooltipCategoria = $tooltip;
			
			//Obtiene servicios suplementarios
			$msg = "";
			$tooltip = "";
			$this->mensajeSuplementarios($linea, $msg, $tooltip);
			$linea->mensajeSuplementarios = $msg;
			$linea->tooltipSuplementarios = $tooltip;
			
		}
		return $result;
	}
	
	
	function getLineasBasicas($usuario,  $filtro){
		$db = & JFactory::getDBO();
		
		$tbIPCentrex 	= $db->nameQuote('#__zipcentrex');
		$tbLineas 		= $db->nameQuote('#__zlineas');
		
		
		$query = "
					SELECT 
						lineas.*
					FROM 
						$tbIPCentrex as ipcentrex,
						$tbLineas as lineas
					WHERE
						ipcentrex.usuario = $usuario AND
						lineas.group_id = ipcentrex.group_id AND
						(
							lineas.linea like '%$filtro%' OR 
							lineas.ext like '%$filtro%' OR
							lineas.direccion like '%$filtro%' 
						)
						";
						
		$db->setQuery($query);
	    $result = $db->loadObjectList();
		foreach($result as $linea){
			//Obtiene categorias
			$msg = "";
			$tooltip = "";
			$this->mensajeCategorias($linea, $msg, $tooltip);
			$linea->mensajeCategoria = $msg;
			$linea->tooltipCategoria = $tooltip;
			
			//Obtiene servicios suplementarios
			$msg = "";
			$tooltip = "";
			$this->mensajeSuplementariosBasico($linea, $msg, $tooltip);
			$linea->mensajeSuplementarios = $msg;
			$linea->tooltipSuplementarios = $tooltip;
			
		}
		return $result;
	}
	
	
	function mensajeCategorias($linea, &$msg, &$tooltip){
		$msg = "";
		$tooltip = "";
		$primero = true;
		
		if($linea->cat_local){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_CAT_LOCAL_MSG');
			$tooltip .= JText::_('IPCENTREX_CAT_LOCAL_TOOLTIP');
			$primero = false;
		}
		
		if($linea->cat_ddn){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_CAT_DDN_MSG');
			$tooltip .= JText::_('IPCENTREX_CAT_DDN_TOOLTIP');
			$primero = false;
			
		}
		
		if($linea->cat_ddi){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_CAT_DDI_MSG');
			$tooltip .= JText::_('IPCENTREX_CAT_DDI_TOOLTIP');
			$primero = false;
			
		}
		
		if($linea->cat_celular){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_CAT_CEL_MSG');
			$tooltip .= JText::_('IPCENTREX_CAT_CEL_TOOLTIP');
			$primero = false;
			
		}
		
		if($linea->cat_901){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_CAT_901_MSG');
			$tooltip .= JText::_('IPCENTREX_CAT_901_TOOLTIP');
			$primero = false;
			
		}
		
		if($linea->cat_113){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_CAT_113_MSG');
			$tooltip .= JText::_('IPCENTREX_CAT_113_TOOLTIP');
			$primero = false;
		}
	}
	
	function mensajeSuplementarios($linea, &$msg, &$tooltip){
		$msg = "";
		$tooltip = "";
		$primero = true;
		
		if($linea->ser_ma){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_MA_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_MA_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_il){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_IL_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_IL_TOOLTIP');
			$primero = false;
			
		}
		
		if($linea->ser_ct){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_CT_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_CT_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_clgt){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_CLGT_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_CLGT_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_ss){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_SS_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_SS_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_gt){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_GT_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_GT_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_te){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_TE_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_TE_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_nm){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_NM_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_NM_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_cs){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_CS_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_CS_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_tm){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_TM_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_TM_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_tl){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_TL_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_TL_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_tll){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_TLL_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_TLL_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_di){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_DI_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_DI_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_do){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_DO_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_DO_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_dnr){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_DNR_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_DNR_TOOLTIP');
			$primero = false;
		}
		
		
		if($linea->ser_cv){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_CV_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_CV_TOOLTIP');
			$primero = false;
		}
		
		
	
	}
	
	function mensajeSuplementariosBasico($linea, &$msg, &$tooltip){
		$msg = "";
		$tooltip = "";
		$primero = true;
		
	
	
		
		if($linea->ser_gt){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_GT_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_GT_TOOLTIP');
			$primero = false;
		}
		
		if($linea->ser_te){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_TE_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_TE_TOOLTIP');
			$primero = false;
		}
			
		if($linea->ser_cv){
			if(!$primero){
				$msg .= " + ";
				$tooltip .= " + ";
			}
			$msg .= JText::_('IPCENTREX_SER_CV_MSG');
			$tooltip .= JText::_('IPCENTREX_SER_CV_TOOLTIP');
			$primero = false;
		}
		
		
	
	}
	
	
	
	
}









