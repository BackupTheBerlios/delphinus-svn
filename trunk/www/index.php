<?php
/**
 * Web Entry Point
 *
 * @package Delphinus
 * @author halt feits <halt.feits@gmail.com>
 * @access public
 */
include_once( dirname( dirname(__FILE__) ). '/app/Delphinus_Controller.php');

Delphinus_Controller::main('Delphinus_Controller', 'index', 'undef');
?>
