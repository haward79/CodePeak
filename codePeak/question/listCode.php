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
<?php require_once('../common/php/string.php'); ?>
<?php require_once('../common/php/database.php'); ?>
<?php require_once('../common/php/language.php'); ?>
<?php

    /*
     *  Retrieve code list from database.
     */

    if(isGetValid('questionId') && is_numeric($_GET['questionId']))  // Valid question id.
    {
        // Retrieve question title.
        $questionId = $_GET['questionId'];
        $dbRetrieve = mysqlQuery('SELECT `title` FROM `question_info` WHERE `id`='.$_GET['questionId'].';');

        if(mysqli_num_rows($dbRetrieve) > 0)  // Question id exists.
        {
            $questionTitle = mysqli_fetch_row($dbRetrieve)[0];
            $dbRetrieve = mysqlQuery('SELECT `question_info`.`title`, `code`.`id`, `code`.`questionId`, `code`.`uploadTime`, `code`.`language` FROM `code` INNER JOIN `question_info` ON `question_info`.`id`=`code`.`questionId` WHERE `questionId`='.$_GET['questionId'].' AND `uploader`=\''.$_SESSION['login_username'].'\' ORDER BY `uploadTime` DESC;');
        }
        else  // Question id doesn't exist.
            $questionId = false;
    }
    else  // Invalid question id.
        $questionId = false;

    // Invalid question id: retrieve all code list.
    if($questionId === false)
        $dbRetrieve = mysqlQuery('SELECT `question_info`.`title`, `code`.`id`, `code`.`questionId`, `code`.`uploadTime`, `code`.`language` FROM `code` INNER JOIN `question_info` ON `question_info`.`id`=`code`.`questionId` WHERE `uploader`=\''.$_SESSION['login_username'].'\' ORDER BY `uploadTime` DESC;');

    $dbRetrieveSize = mysqli_num_rows($dbRetrieve);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>程式碼 - CodePeak</title>
        <link rel="shortcut icon" type="image/png" href="../common/image/icon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" />
        <link rel="stylesheet" href="../common/css/common.css" />
        <link rel="stylesheet" href="../common/css/hSplit.css" />
        <link rel="stylesheet" href="../common/css/infoBox.css" />
        <link rel="stylesheet" href="../common/css/list.css" />
        <link rel="stylesheet" href="../common/css/table.css" />
        <link rel="stylesheet" href="../common/css/form.css" />
        <link rel="stylesheet" href="../login/css/loginForm.css" />
        <link rel="stylesheet" href="css/listCode.css" />
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
                    <h1>程式碼 - <?php echo ($questionId===false)?'全部':('['.$questionId.'] '.$questionTitle) ?></h1>
                    <table class="table_block question_table">
                        <thead>
                            <tr>
                                <th class="question_table_column_id">流水號</th>
                                <th class="question_table_column_title">問題</th>
                                <th class="question_table_column_uploadTime">上傳時間</th>
                                <th class="question_table_column_language">程式語言</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                                /*
                                 *  Show code list.
                                 */

                                if($dbRetrieveSize > 0)
                                {
                                    for($i=0; $i<$dbRetrieveSize; ++$i)
                                    {
                                        $dbExtract = mysqli_fetch_assoc($dbRetrieve);

                                        echo '
                                            <tr>
                                                <td class="question_table_column_id"><a href="viewCode.php?codeId='.$dbExtract['id'].'" target="_self">'.$dbExtract['id'].'</a></td>
                                                <td class="question_table_column_title"><a href="detail.php?questionId='.$dbExtract['questionId'].'" target="_self">'.toHtml('['.$dbExtract['questionId'].'] '.$dbExtract['title']).'</a></td>
                                                <td class="question_table_column_uploadTime">'.str_replace('-', ' . ', explode(' ', $dbExtract['uploadTime'])[0]).'</td>
                                                <td class="question_table_column_language">'.langCodeToString($dbExtract['language']).'</td>
                                            </tr>
                                        '."\n";
                                    }
                                }
                                else
                                {
                                    echo '
                                        <tr>
                                            <td colspan="4">沒有程式碼上傳紀錄。</td>
                                        </tr>
                                    '."\n";
                                }

                            ?>
                        </tbody>
                    </table>
                    <div class="spacer_tiny_block"></div>
                    <input style="background-image:url('../common/image/icon_goBack.png');" class="form_button form_button_withIcon" type="button" value="回上頁" onclick="window.history.go(-1);" />
                </div>

            </div>

            <!-- footer. -->
            <?php require('../common/php/footer.php'); ?>

        </div>

    </body>
</html>

