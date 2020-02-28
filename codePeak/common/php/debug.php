<?php

    /*
     *  Handle exceptions or other errors.
     */

    // 0:disable ; 1:loose ; 2:strict.
    const kDebugMode = 2;

    // Enable or disable php error reporting.
    if(kDebugMode == 2)
        ini_set('display_errors', 1);
    else
        ini_set('display_errors', 0);
    
    function debug_report($msg)
    {
        /*
         *  Print warning or error message on web page and stop the script if needed.
         */

        if(kDebugMode == 2)  // Strict mode.
        {
            echo '<p style="background-color:white; color:red; font-size:20px;">Error : '.$msg."<br />Script terminated.</p>";
            exit(0);
        }
        else if(kDebugMode == 1)  // Loose mode.
            echo '<p style="background-color:white; color:red; font-size:20px;">Warnning : '.$msg."</p>";
        
        // Disabled: no operation.
    }

