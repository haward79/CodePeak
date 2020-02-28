<?php require_once('debug.php'); ?>
<?php

    /*
     *  Provide functions for database connection and SQL query.
     */

    function mysqlQuery($queryStr)
    {
        /*
         *  Make a SQL query and return result.
         */

        $dbCon = mysqli_connect('localhost', 'username', 'password', 'codePeak');

        if($dbCon === false)  // Connect failed.
            debug_report('Database connection error.');
        else  // Connected.
        {
            // Make a query and close conection.
            mysqli_query($dbCon, 'SET NAMES UTF8;');
            $dbRetrieve = mysqli_query($dbCon, $queryStr);
            mysqli_close($dbCon);

            // Check SQL query return value.
            if($dbRetrieve !== false)
                return $dbRetrieve;
            else  // SQL return bad result.
                debug_report('SQL query syntax error.');
        }
    }

