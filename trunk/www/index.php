<?php
/**
 * Web Entry Point
 *
 */
include_once( dirname( dirname(__FILE__) ). '/app/Delphinus_Controller.php');

Delphinus_Controller::main('Delphinus_Controller', 'index', 'undef');
?>
