<?php  

/** 
 * Config.php
 *
 * Configuraci� general de l'aplicaci�.
 */

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->Host       = 'localhost';
$CFG->BaseDades  = 'InGest';
$CFG->Usuari     = 'root';
$CFG->Password   = 'root';

?>