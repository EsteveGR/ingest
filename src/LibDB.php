<?php

/** 
 * LibDB.php
 *
 * Llibreria d'utilitats per a base de dades.
 */

/**
 * ObteCodiValorDesDeSQL
 *
 * Obt� un array que cont� 2 arrays (parell codi-valor) a partir d'una SQL.
 * �s: 
 *
 * @param object $Connexio Connexi� a la base de dades.
 * @param string $SQL Sent�ncia SQL.
 * @param array $CampCodi Nom del camp del codi.
 * @param array $CampValor Nom del camp del valor.
 * @return void Array que cont� 2 arrays (parell codi-valor).
 */
function ObteCodiValorDesDeSQL($Connexio, $SQL, $CampCodi, $CampValor)
{
	$Codi = array();
	$Valor = array();	
	$ResultSet = $Connexio->query($SQL);
	if ($ResultSet->num_rows > 0) {
		$i = 0;
		while($row = $ResultSet->fetch_assoc()) {
			$Codi[$i] = $row[$CampCodi];
			$Valor[$i] = $row[$CampValor];
			$i++;
		}
	};	
	$ResultSet->close();
//	print_r($Codi);
//	print_r($Valor);
	
	return array($Codi, $Valor);
}
 
 ?>
 
