<?php



/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

//Development
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'indesa');


//Production
/*define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'indesago_registe');
define('DB_PASSWORD', 'IFwdoj?O)vgx');
define('DB_NAME', 'indesago_registro');
*/
/* Attempt to connect to MySQL database */
function getConnection(){
    try{
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }
    }
?>