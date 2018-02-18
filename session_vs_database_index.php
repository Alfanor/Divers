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


// Create database, table and twenty lines of data
$user = 'SuperAdmin';
$password = 'SuperAdmin';

try {
    $_SQL = new PDO("mysql:host=localhost", $user, $password);
}

catch(PDOException $e) {
    echo 'Erreur PDO : ' . $e->getMessage();

    exit;
}

$create_database = 'CREATE DATABASE IF NOT EXISTS `session_vs_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci';

$select_database = 'USE session_vs_database';

$create_data_table = '  CREATE TABLE IF NOT EXISTS `data` (
                            `int_4` int(4) UNSIGNED NOT NULL,
                            `string_20` varchar(20) NOT NULL,
                            `string_60` varchar(60) NOT NULL,
                            `string_80` varchar(60) NOT NULL,                            
                              PRIMARY KEY (`int_4`),
                              UNIQUE INDEX `int_4_UNIQUE` (`int_4` ASC)
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

try {
    $_SQL->query($create_database) or die(print_r($_SQL->errorInfo(), true));
    $_SQL->query($select_database) or die(print_r($_SQL->errorInfo(), true));
    $_SQL->query($create_data_table) or die(print_r($_SQL->errorInfo(), true));
}

catch(PDoException $e) {
    echo 'Erreur PDO : ' . $e->getMessage();

    exit;
}

if(!Data::thereIsEnoughLines($_SQL))
    Data::putTwentyLinesInDatabase($_SQL);

// Get data in database
$data = Data::getTwentyLinesOfData($_SQL);

// Store it in $_SESSION
session_start();

$_SESSION['data'] = $data;

echo '<a href="session_vs_database_test.php">Exécuter le test</a>';
?>