<?php  

/** 
 * Config.php
 *
 * Configuraci� general de l'aplicaci�.
 *
 * @author Josep Ciberta
 * @license https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

class Config {
	const Versio         = '1.4';
	const Host           = 'localhost';
	const BaseDades      = 'InGest';
	const Usuari         = 'root';
	const Password       = 'root';
	const Debug          = True; // Si est� activat mostrara m�s informaci�.
	const Demo           = False;
	const Manteniment    = False; 
	const Secret         = '736563726574'; // Clau per a les funcions d'encriptaci� (hexadecimal).
	const EncriptaURL    = True; // Si est� actiu nom�s passar� un par�metre anomenat clau (que contindr� els par�metres originals encriptats).
	const Correu         = 'no.contesteu@inspalamos.cat';
	const PasswordCorreu = Config::Password;
	const UsaDataTables  = True;
	const AutenticacioGoogle = False;
}

// Credencials Google
define('GOOGLE_CLIENT_ID', '***');
define('GOOGLE_CLIENT_SECRET', '***');
define('GOOGLE_REDIRECT_URI', 'http://'.$_SERVER['HTTP_HOST'].'/AutenticacioOath2Google.php');

unset($CFG);
global $CFG;

$CFG = new stdClass();

$CFG->Host           = Config::Host;
$CFG->BaseDades      = Config::BaseDades;
$CFG->Usuari         = Config::Usuari;
$CFG->Password       = Config::Password;
$CFG->Debug          = Config::Debug;
$CFG->Manteniment    = Config::Manteniment;
$CFG->Secret         = hex2bin(Config::Secret); // Clau per a les funcions d'encriptaci�.
$CFG->Correu         = Config::Correu;
$CFG->PasswordCorreu = Config::PasswordCorreu;

// Definici� de l'arrel de l'aplicaci�.
if (defined('STDIN')) {
	// Execuci� de PHP via CLI.
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 
		define('ROOT', 'D:\CASA\Xiber\ingest\src');
		define('INGEST_DATA', 'D:\CASA\Xiber\ingest-data');
		define('UNITAT_XAMPP', 'D');
		//define('ROOT', 'D:/jciberta/ingest/src');
		//define('INGEST_DATA', 'D:/jciberta/ingest-data');
	}
	else if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
		define('ROOT', '/var/www/html/ingest');
		define('INGEST_DATA', '/var/www/ingest-data');
	}
}
else {
	// Execuci� de PHP via web.
	define('ROOT', __DIR__);
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') { 
		// Windows
		define('INGEST_DATA', 'D:/CASA/Xiber/ingest-data');
		define('UNITAT_XAMPP', 'D');
		//define('INGEST_DATA', 'D:/jciberta/ingest-data');
		define('FONT_FILENAME_ARIAL', 'C:\Windows\Fonts\arial.ttf');
	}
	else if (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
		// Linux
		define('INGEST_DATA', '/var/www/ingest-data');
		// Cals instal�lar les fonts
		// sudo apt-get install msttcorefonts
		define('FONT_FILENAME_ARIAL', '/usr/share/fonts/truetype/msttcorefonts/Arial.ttf');
	}	
}

?>