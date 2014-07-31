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


GUIHelper::mensajeSuperior( JText::_('CORREOS_TITULO_PAGINA_INICIO') );
?>
 <!-- BEGIN LOGO -->
  <div class="logo">
    <img src="assets/img/logo-big.png" alt="" /> 
  </div>
  <!-- END LOGO -->
  <!-- BEGIN LOGIN -->
  <div class="content">
   
	<form class="form-vertical forget-form" action="index.html" >
      <h3 class="">Olvido su clave ?</h3>
      <p>Ingrese su correo para reestablecer su clave.</p>
      <div class="control-group">
        <div class="controls">
          <div class="input-icon left">
            <i class="icon-envelope"></i>
            <input class="m-wrap placeholder-no-fix" type="text" placeholder="Correo" name="email" />
          </div>
        </div>
      </div>
      <div class="form-actions">
        <button type="button" id="back-btn" class="btn">
        <i class="m-icon-swapleft"></i> Regresar
        </button>
        <button type="submit" class="btn green pull-right">
        Reestablecer <i class="m-icon-swapright m-icon-white"></i>
        </button>            
      </div>
    </form>   
  </div>
  
  
  <div class="copyright">
    2013 &copy; TusConsultores.com 
  </div>
  
  <script type='text/javascript'>
		document.body.className = 'login';
 </script>
 