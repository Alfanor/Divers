<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

/**
 *  We want to know what is the best system to cache static database data in the volatile memory.
 *      - APC
 *      - SHM
 *
 *  BE CAREFUL : 
 *      - You need to install php-acpu and php-acpu-bc (backward-compatibility)
 *      - You need to uncomment these lines in your php.ini file :
 *          extension=shmop.so
 *          extension=sysvmsg.so
 *          extension=sysvsem.so
 *          extension=sysvshm.so
 */
require_once('Data.php');
require_once('generate_database.php');

// We want 20 Data object
if(!Data::thereIsEnoughLines($_SQL))
    Data::putTwentyLinesInDatabase($_SQL);

// Get the 20 Data object in database
$data = Data::getTwentyLinesOfData($_SQL);

// Store them with APC cache
apc_add('data', $data);

// Store them with SHM cache
$serialized = serialize($data);

$len = strlen($serialized);

$shm_id = shmop_open(0, "c", 0644, $len);
shmop_write($shm_id, $serialized, 0);

// Get Data object from APC
$start_apc = microtime(true);

apc_fetch('data');

$end_apc = microtime(true);

// Get Data object from SHM
$start_shm = microtime(true);

unserialize(shmop_read($shm_id, 0, $len));

shmop_close($shm_id);

$end_shm = microtime(true);

// Get Data object from Database
$start_db = microtime(true);

$data = Data::getTwentyLinesOfData($_SQL);

$end_db = microtime(true);

// Print the result
echo 'Execution time for APC : ' . ($end_apc - $start_apc) . '<br />';
echo 'Execution time for SHM : ' . ($end_shm - $start_shm) . '<br />';
echo 'Execution time for Database : ' . ($end_db - $start_db) . '<br />';
?>