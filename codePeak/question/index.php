<?php if(session_status()==PHP_SESSION_NONE) session_start(); ?>
<?php
    /*
     *  Check auth.
     */

    require_once('../login/index.php');
    
    if(!isLogin())
    {
        clearSession();
        header('Location: ../index.php?loginStatus=5');
        exit(0);
    }
    else if(!login_isAuth(priv_equalHigher, array(4)))
    {
        header('Location: ../index.php?loginStatus=6');
        exit(0);
    }
?>
<?php require_once('../common/php/database.php'); ?>
<?php require_once('../common/php/string.php'); ?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>題庫 - CodePeak</title>
        <link rel="shortcut icon" type="image/png" href="../common/image/icon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" />
        <link rel="stylesheet" href="../common/css/common.css" />
        <link rel="stylesheet" href="../common/css/hSplit.css" />
        <link rel="stylesheet" href="../common/css/infoBox.css" />
        <link rel="stylesheet" href="../common/css/list.css" />
        <link rel="stylesheet" href="../common/css/table.css" />
        <link rel="stylesheet" href="../login/css/loginForm.css" />
        <link rel="stylesheet" href="css/questionList.css" />
    </head>
    <body>
        
        <div id="page_body">
        
            <!-- header. -->
            <?php require('../common/php/header.php'); ?>

            <div class="hSplit_column_container">

                <!-- Left column - menu -->
                <div style="width:25%;" class="hSplit_column_block">
                    <!-- Include common info boxes in sidebar. -->
                    <?php require('../common/php/sideMenu.php'); ?>
                </div>

                <!-- Right column - content -->
                <div style="width:75%;" class="hSplit_column_block">
                    <h1>題庫</h1>
                    <table class="table_block questionList_table">
                        <thead>
                            <tr>
                                <th class="questionList_table_column_id">流水號</th>
                                <th class="questionList_table_column_title">問題</th>
                                <th class="questionList_table_column_lastModify">上次更新</th>
                                <th class="questionList_table_column_privilege">權限</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                                /*
                                 *  Retrieve questions from database.
                                 */

                                $dbRetrieve = mysqlQuery('SELECT * FROM `question_info` ORDER BY `id` DESC;');
                                $dbRetrieveSize = mysqli_num_rows($dbRetrieve);

                                if($dbRetrieveSize != 0)
                                {
                                    for($i=0; $i<$dbRetrieveSize; ++$i)
                                    {
                                        $dbExtract = mysqli_fetch_assoc($dbRetrieve);
                                        $privArray = privExprToArray($dbExtract['privilege']);
                                        $privileged = privArrayToString($privArray);

                                        // Resolve privilege for showing on pgae.
                                        if(login_isAuth(priv_equal, $privArray))
                                            $prevStatus = 'rw';
                                        else
                                            $prevStatus = '--';

                                        // Set privilege description.
                                        if((int)$_SESSION['login_privilege'] <= 2)
                                            $prevDescription = '本題已授權給下列使用者存取\n-------------------------------------\n'.$privileged;
                                        else
                                            $prevDescription = '您有權執行的操作：'.$prevStatus.'\n';

                                        echo '
                                            <tr>
                                                <td class="questionList_table_column_id">'.$dbExtract['id'].'</td>
                                                <td class="questionList_table_column_title"><a href="detail.php?questionId='.$dbExtract['id'].'" target="_self">'.toHtml($dbExtract['title']).'</a></td>
                                                <td class="questionList_table_column_lastModify">'.str_replace('-', ' . ', explode(' ', $dbExtract['lastModify'])[0]).'</td>
                                                <td class="questionList_table_column_privilege" onclick="alert(\''.$prevDescription.'\');">'.$prevStatus.'</td>
                                            </tr>
                                        '."\n";
                                    }
                                }
                                else
                                {
                                    echo '
                                        <tr>
                                            <td colspan="4">當前題庫中沒有題目。</td>
                                        </tr>
                                    '."\n";
                                }

                            ?>
                        </tbody>
                    </table>
                </div>

            </div>

            <!-- footer. -->
            <?php require('../common/php/footer.php'); ?>

        </div>

    </body>
</html>

