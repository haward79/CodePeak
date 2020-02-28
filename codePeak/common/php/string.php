<?php

    /*
     *  Functions for string operation.
     */

    function toHtml($str)
    {
        $str = htmlspecialchars($str);
        $str = str_replace(' ', '&nbsp;', $str);
        $str = str_replace("\n", '<br />', $str);

        return $str;
    }

    function toEscape($str)
    {
        $str = str_replace('\\', '\\\\', $str);
        $str = str_replace('\'', '\\\'', $str);
        $str = str_replace('"', '\\"', $str);

        return $str;
    }

    function isPostValid($varname)
    {
        /*
         *  Check post field is set and not empty.
         */

        if(isset($_POST[$varname]) && $_POST[$varname]!='')
            return true;
        else
            return false;
    }

    function isGetValid($varname)
    {
        /*
         *  Check get field is set and not empty.
         */

        if(isset($_GET[$varname]) && $_GET[$varname]!='')
            return true;
        else
            return false;
    }

    function isSessionValid($varname)
    {
        /*
         *  Check session is set and not empty.
         */

        if(session_status() == PHP_SESSION_NONE)
            session_start();

        if(isset($_SESSION[$varname]) && $_SESSION[$varname]!='')
            return true;
        else
            return false;
    }

    function isAccepableChar($str)
    {
        /*
         *  Check input string conatins acceptable characters only. (To prevent from SQL injection.)
         */

        if(!preg_match('/[^A-Za-z0-9,._\-]/', $str))
            return true;
        else
            return false;
    }

