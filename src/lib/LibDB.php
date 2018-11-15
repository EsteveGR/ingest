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

/** 
 * ResultSetAJSON
 *
 * Passa un ResultSet de MySQL a JSON.
 * https://stackoverflow.com/questions/3430492/convert-mysql-record-set-to-json-string-in-php
 *
 * @param object $ResultSet ResultSet de MySQL.
 * @return string ResultSet en format JSON.
 */
function ResultSetAJSON($ResultSet)
{
	$JSON = '{ "notes": [';
	while($row = $ResultSet->fetch_assoc()) {
		$jsonRow = json_encode($row);
		if ($jsonRow != '')
			$JSON .= $jsonRow.',';
	}
	$JSON = rtrim($JSON, ',').']}';
	return $JSON;
}
 
 ?>
 
