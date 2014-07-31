<?php
/**
 * @version		$Id: default.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Site
 * @subpackage	com_users
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @since		1.5
 */

defined('_JEXEC') or die;

//Get the user type
$user = JFactory::getUser();
$menu = Menu::getMenu($user->tipo);
BasicPageHelper::iniPage( GuiHelper::msgVendedor() , $menu);
PageHelper::initPage("Crear Mensaje", ""); 
$excelUrl = "index.php?option=com_ztadmin&task=adUsuariosExcel";
//echo "grupo".$this->grupoActual;

//print_r($this->data);

?>
	<div class="container-fluid">
				<div class="row-fluid inbox">
					<form class="inbox-compose form-horizontal" id="fileupload"  method="POST" enctype="multipart/form-data">
					
						
						<div class="inbox-compose-btn">
							<button class="btn blue"><i class="icon-check"></i>Enviar</button>
							<a class="btn blue" href="index.php?option=com_ztadmin&task=veEnviarMensajeExcelForm">
							<i class="icon-check "></i>Enviar desde Excel
							</a>
							
							Enviar a todos <input type="checkbox" id="envio_todos" class="m-wrap span12"  name="envio_todos" />
							
							<!--<button class="btn">Discard</button>
							<button class="btn">Draft</button>-->
						</div>
				
						<div class="inbox-control-group mail-to" id='gruposDiv'>
							<label class="control-label">Grupos:</label>
							<div class="controls controls-to">
								<input type="text" class="m-wrap span12" name="grupos" id="grupos" />
							</div>
						</div>
						
						<div class="inbox-control-group mail-to" id='contactosDiv'>
							<label class="control-label">Contactos:</label>
							<div class="controls controls-to">
								<input type="text" class="m-wrap span12" name="contactos" id="contactos" />
							</div>
						</div>
						
						<div class="inbox-control-group" id='telefonosDiv'>
							<label class="control-label">Tel&eacute;fonos:</label>
							<div class="controls">
								<textarea class="span12 inbox-editor inbox-wysihtml5 m-wrap" name="telefonos" id="telefonos" rows="3"></textarea>
							</div>
						</div>
					
						<div class="inbox-control-group">
							<label class="control-label" nowrap="nowrap" >Envio posterior:</label>
							<div class="controls">
								<input type="checkbox" id="envio_posterior" class="m-wrap span12"  name="envio_posterior">
							</div>
						</div>
						
					
						
						<div class="inbox-control-group" id='fecha'>
							<label class="control-label">&nbsp;</label>
							<div class="controls">
								
								  
								<input name='fecha' placeholder="Fecha" type="text" value=""  size="16" class="m-wrap  date-picker" style='border:1px solid green' >
                             
								 
								Hora
								<select name='hora' style='width:8%' >
									<?php 
										for($index=0 ; $index<=23 ; $index++){
									?>
									<option value='<?php echo (($index <=9) ? "0" : "") . $index; ?>'><?php echo $index; ?></option>
									<?php } ?>
								</select>
								Minuto
								<select name='minuto' style='width:8%'>
									<?php 
										for($index=0 ; $index<=59 ; $index++){
									?>
									<option value='<?php echo (($index <=9) ? "0" : "") .  $index; ?>'><?php echo $index; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="inbox-control-group">
							<textarea class="span12 inbox-editor inbox-wysihtml5 m-wrap" name="message" id="message" rows="12"></textarea>
						</div>
						<div id='contador'></div>
						<div class="inbox-compose-attachment">
						
						</div>
						<?php echo JHTML::_( 'form.token' ); ?>
						<input type='hidden' name='option' value='com_ztadmin' />
						<input type='hidden' name='task' value='veenviarmensajesave' />
					</form>
				</div>
			</div>
<?php 
	BasicPageHelper::endPage(); 
	
	//Selecciona el menu
	if($user->tipo == 'V'){
		Menu::setActive("55");
		Menu::setActive("78");
	}
	else if($user->tipo == 'U'){
		Menu::setActive("85");
		Menu::setActive("86");
	}
	
	
	//Use the breadcrumb
	PageHelper::addInitialBreadcrumb("Dashboard","index.php");
	PageHelper::addBreadcrumb("Mensajes","index.php");
	PageHelper::addFinalBreadcrumb("Crear");
	
	//Limpia el breadcrumb
	//echo PageHelper::cleanBreadcrumb();
?>

<script src="templates/chronos/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js" type="text/javascript" ></script>
<script src="templates/chronos/plugins/jquerytokeninput/js/jquery.tokeninput.js" type="text/javascript" ></script> 

<script type='text/javascript'>
jQuery(document).ready(function(){  

	jQuery( "#fecha" ).hide();
	jQuery( "#envio_posterior" ).change(function() {
		if( jQuery( "#envio_posterior" ).is(':checked')  ){
			jQuery( "#fecha" ).show();
		}
		else{
			jQuery( "#fecha" ).hide();
		}
		
	});
	
	jQuery( "#envio_todos" ).change(function() {
		if( jQuery( "#envio_todos" ).is(':checked')  ){
			jQuery( "#gruposDiv" ).hide();
			jQuery( "#contactosDiv" ).hide();
			jQuery( "#telefonosDiv" ).hide();
			
		}
		else{
			jQuery( "#gruposDiv" ).show();
			jQuery( "#contactosDiv" ).show();
			jQuery( "#telefonosDiv" ).show();
		}
		
	});

	if (jQuery().datepicker) {
        jQuery('.date-picker').datepicker();
    }
	
	//http://localhost/appmensajes/components/com_ztadmin/autocomplete/grupos.php?user=
	//http://tusconsultores.com
	$("#grupos").tokenInput("http://sms.smstechnosoft.com/components/com_ztadmin/autocomplete/grupos.php?user=<?php echo $user->id; ?>", 
							{
								theme: "facebook",
								hintText: "Eliga los grupos",
								noResultsText: "No existen grupos con estos datos ...",
								searchingText: "Buscando ....",
								minChars: 0,
								showing_all_results: true,
								tokenLimit: 100,
								preventDuplicates: true
							}
	);
	
	$("#contactos").tokenInput("http://sms.smstechnosoft.com/components/com_ztadmin/autocomplete/contactos.php?user=<?php echo $user->id; ?>", 
							{
								theme: "facebook",
								hintText: "Eliga los contactos",
								noResultsText: "No existen contactos con estos datos ...",
								searchingText: "Buscando ....",
								minChars: 0,
								showing_all_results: true,
								tokenLimit: 100,
								preventDuplicates: true
							}
	);
	
	//Conteo de caracteres
	$("#message").bind("keyup", countCheck);
	
	function countCheck() {
		var textarea = document.getElementById('message');
		var contador = document.getElementById('contador');
		var mensajes = 1;
		var length = textarea.value.length ;
		
		var max = getTamMsg(textarea);
		
		if(length > max){
			var mensajes = Math.ceil((length / 153)) ;
		}
		contador.innerHTML = "Caracteres: " + length + " || Mensajes = " + (mensajes   );
	}
	
	function getTamMsg(textarea){
		var tam = 160;
		/*
		for(i=0; i <textarea.value.length; i++){
			if( (textarea.value[i] >= 'a' && textarea.value[i] <= 'z') || 
				(textarea.value[i] >= 'A' && textarea.value[i] <= 'Z') ||
				(textarea.value[i] >= '0' && textarea.value[i] <= '9') ||
				 textarea.value[i] == ' '
			   ){
			   
			}
			else{
				return 70;
			}
			
		}
		*/
		//alert('tam=' + tam);
		return tam;
	}
	
});
</script>


