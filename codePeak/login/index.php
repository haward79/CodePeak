<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php require_once('../common/php/debug.php'); ?>
<?php require_once('../common/php/string.php'); ?>
<?php require_once('../common/php/database.php'); ?>
<?php

    /*
     *  For login and privilege.
     */

    // Constant for privilege authentication mode.
    const priv_equal = 0;
    const priv_equalHigher = 1;
    const priv_denyEqual = 2;

    function clearSession()
    {
        if(isset($_SESSION['login_username']))
            unset($_SESSION['login_username']);

        if(isset($_SESSION['login_nickname']))
            unset($_SESSION['login_nickname']);

        if(isset($_SESSION['login_privilege']))
            unset($_SESSION['login_privilege']);
    }

    function loginStatusToString($statusCode)
    {
        /*
         *  Convert login status code to login status string.
         */

        // Status code should be numeric.
        if(!is_numeric($statusCode))
            return '發生未知的錯誤，請稍後重試。';

        // Check status by code value.
        switch((int)$statusCode)
        {
            case 0:
                return '登入成功。';

            case 1:
                return '您輸入的密碼有誤，請檢查拼字及大小寫後重試。';

            case 2:
                return '您輸入的帳號不存在，請檢查拼字及大小寫後重試。';

            case 3:
                return '您輸入的帳號或密碼有誤，請檢查拼字後重試。';

            case 4:
                return '帳號與密碼不得為空。';

            case 5:
                return '請先登入以進行權限驗證！';

            case 6:
                return '您當前的帳號權限不足，無法存取該資源。您可以申請帳號提權或以其他已授權帳號登入！';

            default:
                return '發生未知的錯誤，請稍後重試。';
        }
    }

    function verifyLogin($username, $password)
    {
        /*
         *  Verify login by provided username and password and return login status code.
         * 
         *  Login status code:
         *  0:correct ; 1:wrong password ; 2:username doesn't exist ; 3:contains invalid character.
         */

        // Clear session before verify login.
        clearSession();

        // Verify login data.
        if(isAccepableChar($username) && isAccepableChar($password))  // Check special characters.
        {
            $dbRetrieve = mysqlQuery('SELECT `password`, `nickname`, `privilege` FROM `user` WHERE `username`=\''.$username.'\' LIMIT 1;');

            if(mysqli_num_rows($dbRetrieve) > 0)  // Username exists.
            {
                $dbExtract = mysqli_fetch_row($dbRetrieve);
                $hashedPassword = $dbExtract[0];

                if($hashedPassword == crypt($password, $hashedPassword))  // Correct password.
                {
                    $_SESSION['login_username'] = $username;
                    $_SESSION['login_nickname'] = $dbExtract[1];
                    $_SESSION['login_privilege'] = $dbExtract[2];

                    return 0;
                }
                else  // Wrong password.
                    return 1;
            }
            else  // Username doesn't exist.
                return 2;
        }
        else  // Username or password contains invalid character.
            return 3;
    }

    function isLogin()
    {
        /*
         *  Check session to determine whether any user logged in.
         */

        if(isSessionValid('login_username'))  // Login session is valid.
            return true;
        else  // Invalid login session.
        {
            clearSession();

            return false;
        }
    }

    function login_isAuth($mode, $value)
    {
        /*
         *  Return if logged in user is in $value.
         */

        // Check parameters.
        if(!is_numeric($mode) || !is_array($value))
            debug_report('login_isAuth(): Invalid parameter.');

        if(isLogin())  // Logged in.
        {
            if(intdiv(getAuth($_SESSION['login_username'], $mode, $value), 10) == 0)
                return false;
            else
                return true;
        }
        else  // Not logged in.
            return false;
    }

    /*
     *  Functions for privilege string and number handling.
     */

    function privToString($privilegeNumber)
    {
        /*
         *  Convert privilege code to string.
         *  
         *  Privilege:
         *  Even privilege codes mean read only.
         */

        switch($privilegeNumber)
        {
            case 0:
                return '系統管理員';

            case 1:
                return '教師';

            case 2:
                return '教師（唯讀）';

            case 3:
                return '學生';

            case 4:
                return '學生（唯讀）';

            default:
                return '未知';
        }
    }

    function privIsWritable($privString)
    {
        /*
         *  Return if privilege string is writable.
         * 
         *  Privilege:
         *  Even privilege codes or usernames with postfix ^ mean read only.
         */

        if(is_numeric($privString))  // Privilege code.
        {
            $privString = (int)$privString;

            if($privString>0 && $privString%2==0)  // Read only.
                return false;
            else  // Writable.
                return true;
        }
        else  // An username.
        {
            if(substr($privString, strlen($privString)-1, 1) == '^')  // Read only.
                return false;
            else  // Writable.
                return true;
        }
    }

    function privArrayToString($privArray)
    {
        /*
         *  Convert privilege array to string description.
         */
        
        if(!is_array($privArray))
            debug_report('privArrayToString(): Invalid parameter.');

        $privDescription = '';

        for($i=0; $i<sizeof($privArray); ++$i)
        {
            if(is_numeric($privArray[$i]))  // Privilege code.
            {
                // Self contains writability.
                $privDescription = $privDescription.privToString((int)$privArray[$i]).'\n';
            }
            else  // An username.
            {
                // Check writability.
                if(privIsWritable($privArray[$i]))
                    $privDescription = $privDescription.$privArray[$i].'\n';
                else
                    $privDescription = $privDescription.$privArray[$i].'（唯讀）\n';
            }
        }

        return $privDescription;
    }

    function privExprToArray($privilegeStr)
    {
        /*
         *  Convert privilege string to array.
         */

        $extract = explode(' ', $privilegeStr);
        $privilege = array();

        for($i=0; $i<sizeof($extract); ++$i)
        {
            // Check element type.
            if(is_numeric(substr($extract[$i], 0, strlen($extract[$i])-1)) && $extract[$i]{strlen($extract[$i])-1}=='_')
            {
                // This element should be extended.
                for($j=0; $j<=(int)substr($extract[$i], 0, strlen($extract[$i])-1); ++$j)
                    array_push($privilege, $j);
            }
            else  // This element is an username.
                array_push($privilege, $extract[$i]);
        }

        return $privilege;
    }

    function getAuth($subject, $mode, $value)
    {
        /*
         *  Get auth status code.
         * 
         *  Status code has two bits.
         *  First bit: 0:unauth ; *:auth
         *  Second bit: 0:read only ; *:writable
         */

        // Check parameters.
        if(is_string($subject) && !is_numeric($mode) || !is_array($value))
            debug_report('login_isAuth(): Invalid parameter.');

        // Get username's privilege code.
        if(!is_numeric($subject))
            $subjectPrev = getUserPriv($subject);

        // Auth mode.
        if($mode == priv_equal)
        {
            for($i=0; $i<sizeof($value); ++$i)
            {
                if(is_numeric($value[$i]))  // $value[$i] is privilege code.
                {
                    if(is_numeric($subject))  // $subject is privilege code.
                    {
                        if((int)$subject == (int)$value[$i])
                            return 10 + (privIsWritable($subject)?1:0);
                    }
                    else  // $subject is an username.
                    {
                        if($subjectPrev == (int)$value[$i])
                            return 10 + (privIsWritable($subjectPrev)?1:0);
                    }
                }
                else  // $value[$i] is an username.
                {
                    if(is_numeric($subject))  // $subject is privilege code.
                    {
                        // Inappropriate comparison.
                        return 00;
                    }
                    else  // $subject is an username.
                    {
                        if($subject == $value[$i])
                            return 10 + (privIsWritable($subjectPrev)?1:0);
                    }
                }
            }
        }
        else if($mode == priv_equalHigher)
        {
            for($i=0; $i<sizeof($value); ++$i)
            {
                if(is_numeric($value[$i]))  // $value[$i] is privilege code.
                {
                    if(is_numeric($subject))  // $subject is privilege code.
                    {
                        if((int)$subject <= (int)$value[$i])
                            return 10 + (privIsWritable($subject)?1:0);
                    }
                    else  // $subject is an username.
                    {
                        if($subjectPrev <= (int)$value[$i])
                            return 10 + (privIsWritable($subjectPrev)?1:0);
                    }
                }
                else  // $value[$i] is an username.
                {
                    if(is_numeric($subject))  // $subject is privilege code.
                    {
                        // Inappropriate comparison.
                        return 00;
                    }
                    else  // $subject is an username.
                    {
                        if($subject == $value[$i])
                            return 10 + (privIsWritable($subjectPrev)?1:0);
                    }
                }
            }
        }
        else if($mode == priv_denyEqual)
        {
            $oppo = getAuth($subject, priv_equal, $value);

            if(intdiv($oppo, 10) == 1)
                return 00;
            else
                return 11;
        }

        // No appropriate privilege for user.
        return 00;
    }

    function getUserPriv($username)
    {
        $dbRetrieve = mysqlQuery('SELECT `privilege` FROM `user` WHERE `username`=\''.$username.'\' LIMIT 1;');

        if(mysqli_num_rows($dbRetrieve) != 0)
            return (int)mysqli_fetch_row($dbRetrieve)[0];
        else
            return -1;
    }

     