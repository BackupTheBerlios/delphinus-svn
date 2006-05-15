<?php
/**
 *	{$action_name}.php
 *
 *	@author		{$author}
 *	@package	Delphinus
 *	@version	$Id$
 */
chdir(dirname(__FILE__));
include_once('{$dir_app}/Delphinus_Controller.php');

ini_set('max_execution_time', 0);

Delphinus_Controller::main_CLI('Delphinus_Controller', '{$action_name}');
?>
