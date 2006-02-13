<?php
/**
 * Delphinus RSS Crawler
 *
 * @package Delphinus
 * @author halt <halt.hde@gmail.com>
 * @access public
 */
include_once(dirname(dirname(__FILE__)).'/app/Delphinus_Controller.php');

Delphinus_Controller::main_CLI('Delphinus_Controller', 'Crawler', 'undef');
?>
