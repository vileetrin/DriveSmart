<?php
    if (!class_exists('Page')) {
        require_once 'Class/pages.php';
    }
    $bar = new PaymentPage();
    $bar->ShowHeader();
    $bar->ShowContent();
    $bar->ShowFooter();
?>