<?php
/**
 * @version		$Id: view.html.php 21023 2011-03-28 10:55:01Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Form login
 *
 * @package		
 * @subpackage	
 * @since		
 */
class ViewVeReportExecute extends JView
{
	protected $form;
	protected $params;
	protected $state;
	protected $user;

	/**
	 * Method to display the view.
	 *
	 * @param	string	The template file to include
	 * @since	1.5
	 */
	public function display($tpl = null)
	{
		
		//print_r(JRequest::get('post'));
		ini_set('display_errors','On');
		
		date_default_timezone_set('America/Bogota');

		$reportId = JRequest::getVar('reportCwId');
		
		$model 				= $this->getModel('ZWebReports');
		$log 				= $model->reportLog($reportId);
		$report 			= $model->getReport($reportId);
		$report->parameters = $model->getQueryParameters($report->query);
		
		//echo "query : {$report->query}";
		
		if(is_array($report->parameters)){
			foreach($report->parameters as $parameter){
				$value = JRequest::getVar($parameter);
				$find = "/\{$parameter\}/";
				if($parameter != "USER_ID"){
					$report->query = preg_replace($find, $value, $report->query );
				}
			}
		}
		
		//Actualiza el id del usario en caso de que exista
		$user = JFactory::getUser();
		$find = "/\{USER_ID\}/";
		$report->query = preg_replace($find, $user->id, $report->query );
		
		$model->executeReport($report);
		
		//Reemplazar parametros en consulta
		
		/*$id 	= JRequest::getVar('id');
		
		$model 		= $this->getModel('ZWebReports');
		$report 	= $model->getReport($id);
		
		$this->assignRef('report', $report);*/
		parent::display($tpl);
	}

	
}
