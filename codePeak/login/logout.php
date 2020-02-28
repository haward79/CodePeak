<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('index.php'); ?>
<?php

    /*
     *  Logout: clear all session.
     */

    clearSession();

    header('Location: ../');

    