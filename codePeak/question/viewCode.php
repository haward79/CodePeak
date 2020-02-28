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
        require_once('../common/php/string.php');

        if(isGetValid('questionId'))
        {
            header('Location: detail.php?loginStatus=6&questionId='.$_GET['questionId']);
            exit(0);
        }
        else
        {
            header('Location: index.php?loginStatus=6');
            exit(0);
        }
    }
?>
<?php require_once('../common/php/string.php'); ?>
<?php require_once('../common/php/database.php'); ?>
<?php require_once('../common/php/language.php'); ?>
<?php

    /*
     *  Retrieve code.
     */

    if(isGetValid('codeId') && is_numeric($_GET['codeId']) && (int)$_GET['codeId']>0)
    {
        $dbRetrieve = mysqlQuery('SELECT `question_info`.`title`, `code`.* FROM `code` INNER JOIN `question_info` ON `question_info`.`id`=`code`.`questionId` WHERE `code`.`id`='.$_GET['codeId'].' LIMIT 1;');

        if(mysqli_num_rows($dbRetrieve) > 0)  // Code id exists.
            $dbExtract = mysqli_fetch_assoc($dbRetrieve);
        else  // Code id doesn't exist.
        {
            header('Location: https://httpstatus.haward79.tw/?statusCode=404');
            exit(0);
        }
    }
    else
    {
        header('Location: https://httpstatus.haward79.tw/?statusCode=404');
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>檢視程式碼 - CodePeak</title>
        <link rel="shortcut icon" type="image/png" href="../common/image/icon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" />
        <link rel="stylesheet" href="../common/css/common.css" />
        <link rel="stylesheet" href="../common/css/hSplit.css" />
        <link rel="stylesheet" href="../common/css/infoBox.css" />
        <link rel="stylesheet" href="../common/css/list.css" />
        <link rel="stylesheet" href="../common/css/form.css" />
        <link rel="stylesheet" href="../common/css/box.css" />
        <link rel="stylesheet" href="../login/css/loginForm.css" />
        <link rel="stylesheet" href="css/viewCode.css" />
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
                    <h1>檢視程式碼</h1>
                    <div class="hSplit_column_container">
                        <div style="width:calc(50% - 10px);" class="hSplit_column_block box_element_block">
                            <h1>總覽</h1>
                            <ul class="unorderedList_explicit_block" style="margin:10px;">
                                <li>流水號：<?php echo $dbExtract['id']; ?></li>
                                <li>問題：<a href="detail.php?questionId=<?php echo $dbExtract['questionId'] ?>" target="_blank"><?php echo '['.$dbExtract['questionId'].'] '.toHtml($dbExtract['title']); ?></a></li>
                                <li>程式語言：<?php echo langCodeToString($dbExtract['language']); ?></li>
                                <li>上傳者：<?php echo $dbExtract['uploader']; ?></li>
                                <li>上傳時間：<?php echo str_replace('-', '.', $dbExtract['uploadTime']); ?></li>
                            </ul>
                        </div>
                        <div style="width:calc(50% - 10px);" class="hSplit_column_block box_element_block">
                            <h1>備註</h1>
                            <p class="box_content_block"><?php echo toHtml($dbExtract['comment']); ?></p>
                        </div>
                    </div>
                    <?php

                        $extractedFile = explode('/0', $dbExtract['code']);

                        for($i=0; $i<sizeof($extractedFile); ++$i)
                        {
                            $filename = explode("\n", $extractedFile[$i])[0];
                            $sourcecode = str_replace($filename."\n", '', $extractedFile[$i]);

                            if($filename=='' || $sourcecode=='')
                                continue;

                            echo '
                                <div class="box_element_block">
                                    <h1>程式碼：'.toHtml($filename).'</h1>
                                    <textarea class="codeView_text_textarea" readonly>'.$sourcecode.'</textarea>
                                </div>
                            ';
                        }

                    ?>

                    <input style="background-image:url('../common/image/icon_goBack.png');" class="form_button form_button_withIcon" type="button" value="回上頁" onclick="window.history.go(-1);" />
                </div>

            </div>

            <!-- footer. -->
            <?php require('../common/php/footer.php'); ?>

        </div>

    </body>
</html>

