<?php

/** 
 * LibAdministracio.php
 *
 * Llibreria d'utilitats per a l'administració de l'aplicació.
 *
 * @author Josep Ciberta
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

//require_once('../Config.php');
require_once(ROOT.'/lib/LibForms.php');

/**
 * Classe que encapsula les utilitats per a l'administració.
 */
class Administracio extends Objecte
{
	/**
	* Connexió a la base de dades.
	* @access public 
	* @var object
	*/    
//	public $Connexio;

	/**
	* Usuari autenticat.
	* @access public 
	* @var object
	*/    
//	public $Usuari;

	/**
	 * Constructor de l'objecte.
	 * @param object $conn Connexió a la base de dades.
	 * @param object $user Usuari de l'aplicació.
	 */
/*	function __construct($con, $user) {
		$this->Connexio = $con;
		$this->Usuari = $user;
	}*7

	/**
	 * Obté les metadades d'una taula.
	 * @param string $Taula Taula.
	 * @return array Metadades de la taula.
	 */
	public function ObteMetadades(string $Taula): array {
		$Retorn = [];
		$SQL = 'DESCRIBE '.$Taula;
		$ResultSet = $this->Connexio->query($SQL);
		while($row = $ResultSet->fetch_assoc()) {
			array_push($Retorn, $row);
		}
		return $Retorn;
	}
	
	/**
	 * Obté la clau primària a partir de les metadades d'una taula.
	 * @param array Metadades de la taula.
	 * @return string Clau primària. Si n'hi ha més d'una, es separen per comes.
	 */
	public function ClauPrimariaDesDeMetadades(array $Metadades): string {
		$aClauPrimaria = [];
		for ($i=0; $i<count($Metadades); $i++) {
			$row = $Metadades[$i];
			if ($row['Key'] == 'PRI') 
				array_push($aClauPrimaria, $row['Field']);
		}
		$Retorn = implode(",", $aClauPrimaria);
		return $Retorn;
	}
	
	/**
	 * Crea la taula amb la informació de les metadades d'una taula.
	 * @param array Metadades de la taula.
	 * @return string HTML generat.
	 */
	public function CreaTaulaMetadades(array $Metadades): string {
		$sRetorn = "<TABLE>";
		$PrimerCop = True;
		for ($i=0; $i<count($Metadades); $i++) {
			$row = $Metadades[$i];
			$keys = array_keys($row);
			if ($PrimerCop) {
				$sRetorn .= "<THEAD>";
				for ($j=0; $j<count($keys); $j++) {
					$sRetorn .= "<TH>".$keys[$j]."</TH>";
				}
				$sRetorn .= "</THEAD>";
				$PrimerCop = False;
			}
			$sRetorn .= "<TR>";
			for ($j=0; $j<count($keys); $j++) {
				$sRetorn .= "<TD>".$row[$keys[$j]]."</TD>";
			}
			$sRetorn .= "</TR>";			
		}		
		$sRetorn .= "</TABLE>";		
		return $sRetorn;
	}
	
	/**
	 * Escriu la fitxa per a l'edició d'un registre d'una taula.
	 * @param string $Taula Taula.
	 * @param string $Clau Clau primària.
	 * @param string $Valor Valor de la clau primària.
	 */
	public function EscriuFitxaEdicioRegistre(string $Taula, string $Clau, string $Valor) {
		$Metadades = $this->ObteMetadades($Taula);
		$frm = new FormFitxa($this->Connexio, $this->Usuari);
		$frm->Titol = 'Edició registre';
		$frm->Taula = $Taula;
		$frm->ClauPrimaria = $Clau;
		$frm->Id = $Valor;
		for ($i=0; $i<count($Metadades); $i++) {
			$row = $Metadades[$i];
			$Tipus = strtoupper($row['Type']);
			$aTipus = explode('(', $Tipus);

			$off = ($row['Key'] == 'PRI') ? [Form::offNOMES_LECTURA] : [];
			if (($row['Key'] == 'PRI') && ($row['Extra'] == 'auto_increment'))
				$frm->AutoIncrement = True;

			switch ($aTipus[0]) {
				case 'INT':
					$frm->AfegeixEnter($row['Field'], $row['Field'], 50, $off);
					break;
				case 'CHAR':
				case 'VARCHAR':
					$frm->AfegeixText($row['Field'], $row['Field'], 200, []);
					break;
				case 'BIT':
					$frm->AfegeixCheckBox($row['Field'], $row['Field']);
					break;
				case 'DATE':
					$frm->AfegeixData($row['Field'], $row['Field']);
					break;
				case 'DATETIME':
				case 'TIME':
					$frm->AfegeixText($row['Field'], $row['Field'], 50, []);
					break;
			}
		}
		$frm->EscriuHTML();
	}
}

 ?>