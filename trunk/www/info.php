<?php
/**
 * Delphinus Debug Info
 *
 * @package Delphinus
 * @author halt <halt.hde@gmail.com>
 */
include_once('/home/halt/codes/delphinus/app/Delphinus_Controller.php');

define('ETHNA_DEBUG', true);

Delphinus_Controller::main(
    'Delphinus_Controller',
    array('__ethna_info__')
);
?>
