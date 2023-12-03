<?php
        session_start();

        if(!isset($_SESSION['id']))
        {
                header('location: ../signin_system/auth.php');
                exit();
        }

        include 'common_php/topnav_head.php';
?>
        <link rel="stylesheet" href="styles/style.css">