<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

/**
 * We want to know what is the more efficient -faster- way between use SESSION to store all data of a member
 * Or request the database on every single page
 * To do this, we use two scripts :
 *  - This one :
 *      -> Create database and data in it
 *      -> Create session and store database data in it
 *  - The second one :
 *      -> Load data from database
 *      -> Load data from the session
 */
require_once("Data.php");
require_once('generate_database.php');

if(!Data::thereIsEnoughLines($_SQL))
    Data::putTwentyLinesInDatabase($_SQL);

// Get data in database
$data = Data::getTwentyLinesOfData($_SQL);

// Store it in $_SESSION
session_start();

$_SESSION['data'] = $data;

// We want to be sure that many data in SESSION is faster than just one or two little SQL request
$more_data = array();

for($i = 0; $i < 100; $i++) {
    $more_data[] = array($i, substr(hash('sha512', rand()), 0, 20), substr(hash('sha512', rand()), 0, 60), substr(hash('sha512', rand()), 0, 80));
}

$_SESSION['more_data'] = $more_data;

echo '<a href="session_vs_database_test.php">Exécuter le test</a>';
?>