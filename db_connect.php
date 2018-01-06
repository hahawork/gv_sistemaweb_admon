<?php
 
/**
 * A class file to connect to database
 */
class DB_CONNECT {
 
    // constructor
    function __construct() {
        // connecting to database
        $this->connect();
    }
 
    // destructor
    function __destruct() {
        // closing db connection
        $this->close();
    }
 
    /**
     * Function to connect with database
     */
    function connect() {
		
		/*$conndb = mysql_connect("localhost", "grupovalordb", "qD2Hsh7E3Vdph4nN");
		mysql_select_db("grupovalordb", $conndb);
		echo mysql_errno($conndb) . ": " . mysql_error($conndb). "\n";
		mysql_select_db("grupovalordb", $conndb);*/
	
        // import database connection variables
        require_once __DIR__ . '/db_config.php';
 
        // Connecting to mysql database
        $con = mysql_connect(DB_SERVER, DB_USER, DB_PASSWORD) or die(mysql_error());
		mysql_set_charset("SET NAMES 'utf-8'", $conn);
 
        // Selecing database
        $db = mysql_select_db(DB_DATABASE) or die(mysql_error());
 
        // returing connection cursor
        return $con;
    }
 
    /**
     * Function to close db connection
     */
    function close() {
        // closing db connection
        mysql_close();
    }
 
}
 
?>