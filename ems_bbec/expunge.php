<?php
error_reporting(E_ERROR);

spl_autoload_register('emsAutoloader');

function emsAutoloader($className){
    $path = 'models/';
    include $path.$className.'.php';
}

// Class instances
$ems = new EMSHub();
$result = $ems->expunge();