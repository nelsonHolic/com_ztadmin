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
 *
 * @author      aquintero
 * @package		Joomla
 * @subpackage	ztelecliente
 * @since 1.6
 */


		
class ModelBackup extends JModel{
    /**
	 * Constructor
	 */
	function __construct() {
		parent::__construct();
	}
	
	
	function backupConcurso($fileName){
		$this->backup_tables($fileName, 'localhost','root','','concursomundial');
	}
	
	/**
	* Lista de usuarios
	*/
	function backup_tables($fileName, $host,$user,$pass,$name,$tables = '*')
	{
	

		$data = "";
		$link = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$link);
		
		//get all of the tables
		if($tables == '*')
		{
			$tables = array();
			$result = mysql_query('SHOW TABLES');
			while($row = mysql_fetch_row($result))
			{
				$tables[] = $row[0];
			}
		}
		else
		{
			$tables = is_array($tables) ? $tables : explode(',',$tables);
		}
		
		//cycle through
		foreach($tables as $table)
		{
			$result = mysql_query('SELECT * FROM '.$table);
			$num_fields = mysql_num_fields($result);
			
			$data.= 'DROP TABLE '.$table.';';
			$row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
			$data.= "\n\n".$row2[1].";\n\n";
			
			for ($i = 0; $i < $num_fields; $i++) 
			{
				while($row = mysql_fetch_row($result))
				{
					$data.= 'INSERT INTO '.$table.' VALUES(';
					for($j=0; $j<$num_fields; $j++) 
					{
						$row[$j] = addslashes($row[$j]);
						$row[$j] = preg_replace("/\n/","/\\n/",$row[$j]);
						if (isset($row[$j])) { $data.= '"'.$row[$j].'"' ; } else { $data.= '""'; }
						if ($j<($num_fields-1)) { $data.= ','; }
					}
					$data.= ");\n";
				}
			}
			$data.="\n\n\n";
		}
		
		$fechaHoy = date('Y-m-d');
		//$handle = fopen('db-backup-$name'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
		//$name = "/backups/db-backup-$name-$fechaHoy"."-".".sql";
		$name = "backups/db-backup-$fileName-$fechaHoy-".time().".sql";
		
		$handle = fopen($name,'w+');
		fwrite($handle,$data);
		fclose($handle);
		
		mysql_select_db("mundialuneadm",$link);
	}
	
	
	
	
	
}









