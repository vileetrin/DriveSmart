<?php
    if (!class_exists('Page')) {
        require_once 'Class/pages.php';
    }
    $bar = new ReservationsPage();
    $bar->ShowHeader();
    $bar->ShowContent();
    $bar->ShowFooter();
?>