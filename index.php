<?php

    require_once('class/dblogin.inc.php');
    require_once('class/config.inc.php');
    require_once("class/Pagination.class.php");
    require_once('includes/functions.php');

    require_once('includes/global_meta.php');
    if(file_exists('pages/'.$page.'/local.css')) {
        echo '<link rel="stylesheet" type="text/css" href="pages/'.$page.'/local.css"/>';
    }
    require_once('includes/header.php');

    if(file_exists('pages/'.$page.'/display.php')) {
        if(file_exists('pages/'.$page.'/local_vars.php')) {
            require('pages/'.$page.'/local_vars.php');
        }
        require 'pages/'.$page.'/display.php';
    } else {
        require 'pages/home/display.php';
    }

    require('includes/footer.php'); 

    if(file_exists('pages/'.$page.'/local.js')) {
        echo '<script type="text/javascript" src="pages/'.$page.'/local.js"></script>';
    }