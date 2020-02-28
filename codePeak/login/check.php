<?php require_once('../common/php/string.php'); ?>
<?php require_once('index.php'); ?>
<?php

    /*
     *  Check login data fields and redirect to index.
     */

    if(isPostValid('loginForm_field_username') && isPostValid('loginForm_field_password'))
        $status = verifyLogin($_POST['loginForm_field_username'], $_POST['loginForm_field_password']);

    header('Location: ../index.php?loginStatus='.$status);

     