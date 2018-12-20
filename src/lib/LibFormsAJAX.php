<?php

/** 
 * LibFormsAJAX.php
 *
 * Accions AJAX per a la llibreria de formularis.
 *
 * @author Josep Ciberta
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 * @version 1.0
 */
 

require_once('../Config.php');
require_once('LibForms.php');
require_once('LibCripto.php');

session_start();
if (!isset($_SESSION['usuari_id'])) 
	header("Location: ../index.html");

$conn = new mysqli($CFG->Host, $CFG->Usuari, $CFG->Password, $CFG->BaseDades);
if ($conn->connect_error) {
  die("ERROR: Unable to connect: " . $conn->connect_error);
}


// print 'AJAX';


if (($_SERVER['REQUEST_METHOD'] === 'POST') && (isset($_REQUEST['accio']))) {
	if ($_REQUEST['accio'] == 'ActualitzaTaula') {
		$cerca = $_REQUEST['cerca'];
		$FormSerialitzatEncriptat = $_REQUEST['frm'];
		$FormSerialitzat = SaferCrypto::decrypt(hex2bin($FormSerialitzatEncriptat), hex2bin(Form::Secret));
		$frm = unserialize($FormSerialitzat);
		$frm->Connexio = $conn; // La connexi� MySQL no es serialitza/deserialitza b�
		$frm->Filtre = $cerca; 
		print $frm->GeneraTaula();
	}
	else if ($_REQUEST['accio'] == 'DesaFitxa') {
		$jsonForm = $_REQUEST['form'];
//print 'DesaFitxa.jsonForm: '.$jsonForm;
		$data = json_decode($jsonForm);
		$sCamps = '';
		$sValues = '';
		foreach($data as $Valor) {
			if ($Valor->name == 'hid_Taula') 
				$Taula = $Valor->value;
			else if ($Valor->name == 'hid_ClauPrimaria') 
				$ClauPrimaria = $Valor->value;
			else if ($Valor->name == 'hid_AutoIncrement') 
				$AutoIncrement = $Valor->value;
			else if ($Valor->name == 'hid_Id') 
				$Id = $Valor->value;
			else {
				$Tipus = substr($Valor->name, 0, 3);
				switch ($Tipus) {
					case 'edt':
						// Camp text
						$sCamps .= substr($Valor->name, 4).", ";
						if ($Valor->value == '')
							$sValues .= "NULL, ";
						else
							$sValues .= "'".$Valor->value."', ";
						break;
					case 'chb':
						// Camp checkbox
						$sCamps .= substr($Valor->name, 4).", ";
						$sValues .= (($Valor->value == '') || ($Valor->value == 0)) ? '0, ' : '1, ';
						break;
					case 'lkh':
						if (substr($Valor->name, -6) != '_camps') {
							// Camp lookup
							$sCamps .= substr($Valor->name, 4).", ";
							$sValues .= ($Valor->value == '') ? "NULL, " : $Valor->value.", ";
							//if ($Valor->value == '')
								//$sValues .= "NULL, ";
							//else
								//$sValues .= "'".$Valor->value."', ";
//print '<BR>Camp: '.$Valor->name . ' <BR> Value: '.$Valor->value . '<BR>';
//print_r($Valor);
						}
						break;
				}
			}
		}
		$sCamps = substr($sCamps, 0, -2);
		$sValues = substr($sValues, 0, -2);
//print 'Camps: '.$sCamps . ' <BR> Values: '.$sValues;
		if ($Id == 0) {
			// INSERT
			if ($AutoIncrement) {
				$SQL = "INSERT INTO ".$Taula." (".$sCamps.") VALUES (".$sValues.")";
			}
			else {
				$sCamps = $ClauPrimaria.', '.$sCamps;
				$sValues = '(SELECT MAX('.$ClauPrimaria.')+1 FROM '. $Taula.'), '.$sValues;
				$SQL = "INSERT INTO ".$Taula." (".$sCamps.") SELECT ".$sValues;
			}
		}
		else {
			// UPDATE
			$SQL = "UPDATE ".$Taula." SET ";
			$aCamps = explode(",", TrimXX($sCamps));
			$aValues = explode(",", Trim($sValues));
			for($i=0; $i < count($aCamps); $i++) {
				$SQL .= $aCamps[$i].'='.trim($aValues[$i]).', ';
			}
			$SQL = substr($SQL, 0, -2);
			$SQL .= ' WHERE '.$ClauPrimaria.'='.$Id;
			
		}
		$SQL = utf8_decode($SQL);
		$conn->query($SQL);
print 'SQL: '.$SQL;
	}
	else {
		if ($CFG->Debug)
			print "Acci� no suportada. Valor de $_POST: ".json_encode($_POST);
		else
			print "Acci� no suportada.";
	}
}
else 
    print "ERROR. No hi ha POST o no hi ha acci�.";

?>
