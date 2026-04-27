<?php
session_start();

$json = file_get_contents(__DIR__ . '/pages.json');
$pages = json_decode($json);

$currentPage = basename($_SERVER['PHP_SELF']);

// carica il DB se serve
if (in_array($currentPage, $pages->DBPages)) {
    require_once __DIR__ . '/DBHandler.php';
}

// se la pagina richiede login e non sei loggato, vai al login
if (in_array($currentPage, $pages->loggedInPages)) {
    if (!isset($_SESSION['utente_id'])) {
        header('Location: /ProgettoFinale_Ilmondodellauto/userpages/login.php');
        exit;
    }
}

// carica la navbar giusta
if (in_array($currentPage, $pages->adminpages)) {
    require_once __DIR__ . '/adminNavbar.php';
} else if (in_array($currentPage, $pages->userpages)) {
    require_once __DIR__ . '/navbar.php';
}  
