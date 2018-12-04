<?php

/** 
 * LibMatricula.php
 *
 * Llibreria d'utilitats per a la matriculaci�.
 *
 * @author Josep Ciberta
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

/**
 * CreaMatricula
 *
 * Crea la matr�cula per a un alumne. Quan es crea la matr�cula:
 *   1. Pel nivell que sigui, es creen les notes, una per cada UF d'aquell cicle
 *   2. Si l'alumne �s a 2n, l'aplicaci� ha de buscar les que li han quedar de primer per afegir-les
 * �s: 
 *
 * @param object $Connexio Connexi� a la base de dades.
 * @param integer $CursId Id del curs.
 * @param integer $AlumneId Id de l'alumne.
 * @param integer $CicleId Id del cicle.
 * @param integer $Nivell Nivell (1r o 2n).
 * @param integer $Grup Grup (cap, A, B, C).
 * @return integer Valor de retorn: 0 Ok, -1 Alumne ja matriculat, -2 Error.
 */
function CreaMatricula($Connexio, $Curs, $Alumne, $Cicle, $Nivell, $Grup)
{
	$SQL = " CALL CreaMatricula(".$Curs.", ".$Alumne.", ".$Cicle.", ".$Nivell.", '".$Grup."', @retorn)";
	
	// Obtenci� de la variable d'un procediment emmagatzemat.
	// http://php.net/manual/en/mysqli.quickstart.stored-procedures.php
	if (!$Connexio->query("SET @retorn = -2") || !$Connexio->query($SQL)) {
		echo "CALL failed: (" . $Connexio->errno . ") " . $Connexio->error;
	}

	if (!($res = $Connexio->query("SELECT @retorn as _retorn"))) {
		echo "Fetch failed: (" . $Connexio->errno . ") " . $Connexio->error;
	}

	$row = $res->fetch_assoc();
	return $row['_retorn'];	
}
 
 
 
 ?>
