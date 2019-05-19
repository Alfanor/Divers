<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

/**
 * We want to know what is the more efficient -faster- way between use SESSION to store all data of a member
 * Or request the database on every single page
 * To do this, we use two scripts :
 *  - This one :
 *      -> Load data from session
 *      -> Load data from database
 *  - The other one :
 *      -> Create database and data in it
 *      -> Create session and store database data in it
 */
require_once("Data.php");

// We need the connexion in all cases i think
$user = 'SuperAdmin';
$password = 'SuperAdmin';
$database = 'session_vs_database';

try {
    $_SQL = new PDO("mysql:dbname=" . $database . ";host=localhost", $user, $password);
}

catch(PDOException $e) {
    echo 'Erreur PDO : ' . $e->getMessage();

    exit;
}

$start_time = microtime(true);

session_start(); // No more to do I suppose

// But, we want to read a file with a number in it :)
$number = file_get_contents('number_to_read');

$time_after_session = microtime(true);

$data = Data::getTwentyLinesOfData($_SQL);

$final_after_sql = microtime(true);

session_destroy();

echo 'Durée de récupération de la session : ' . ($time_after_session - $start_time) . '<br />';
echo 'Durée de récupération dans la BDD : ' . ($final_after_sql - $time_after_session) . '<br /><br />';

echo '<a href="session_vs_database_index.php">Retour à l\'index</a>';
?>