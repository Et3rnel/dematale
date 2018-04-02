<?php

$host = 'localhost';
$dbName = 'dematale';
$dbUser = 'root';
$dbPassword = '';

try {
    $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
    $bdd                            = new PDO('mysql:host=' . $host . ';dbname=' . $dbName, $dbUser, $dbPassword, $pdo_options);
}
catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
