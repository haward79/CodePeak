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
<?php

    /*
     *  Fetch question.
     */

    if(isGetValid('questionId'))
    {
        $dbRetrieve = mysqlQuery('SELECT `question_info`.*, `question_detail`.* FROM `question_info` INNER JOIN `question_detail` ON `question_info`.`id`=`question_detail`.`id` WHERE `question_info`.`id`='.$_GET['questionId'].' LIMIT 1;');
        
        if(mysqli_num_rows($dbRetrieve) > 0)
        {
            $dbExtract = mysqli_fetch_assoc($dbRetrieve);

            $qTitle = toHtml($dbExtract['title']);
            $qPrivilege = privExprToArray($dbExtract['privilege']);
            $qLastModify = $dbExtract['lastModify'];
            $qContent = toHtml($dbExtract['content']);
            $qInstruct = array(toHtml($dbExtract['inputInstruct']), toHtml($dbExtract['outputInstruct']));
            $qSample = array($dbExtract['inputSample'], $dbExtract['outputSample']);
            $qHint = toHtml($dbExtract['hint']);
        }
        else
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
        <title>[題目] <?php echo $qTitle ?> - CodePeak</title>
        <link rel="shortcut icon" type="image/png" href="../common/image/icon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" />
        <link rel="stylesheet" href="../common/css/common.css" />
        <link rel="stylesheet" href="../common/css/hSplit.css" />
        <link rel="stylesheet" href="../common/css/infoBox.css" />
        <link rel="stylesheet" href="../common/css/list.css" />
        <link rel="stylesheet" href="../common/css/form.css" />
        <link rel="stylesheet" href="../common/css/box.css" />
        <link rel="stylesheet" href="../login/css/loginForm.css" />
        <link rel="stylesheet" href="css/question.css" />
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
                    <h1 class="question_text_title">[題目<?php echo $_GET['questionId']; ?>] <?php echo $qTitle; ?></h1>
                    <p class="question_text_subtitle">最後修改：<?php echo $qLastModify; ?></p>
                    <div class="box_element_block">
                        <h1>題目說明</h1>
                        <p class="box_content_block"><?php echo $qContent; ?></p>
                    </div>
                    <div class="hSplit_column_container">
                        <div style="width:calc(50% - 10px);" class="hSplit_column_block box_element_block">
                            <h1>輸入測資</h1>
                            <p class="box_content_block"><?php echo $qInstruct[0]; ?></p>
                        </div><!--
                     --><div style="width:calc(50% - 10px);" class="hSplit_column_block box_element_block">
                            <h1>輸出測資</h1>
                            <p class="box_content_block"><?php echo $qInstruct[1]; ?></p>
                        </div>
                    </div>
                    <div class="hSplit_column_container">
                        <div style="width:calc(50% - 10px);" class="hSplit_column_block box_element_block">
                            <h1>範例輸入</h1>
                            <textarea class="question_text_textarea" readonly><?php echo $qSample[1]; ?></textarea>
                        </div><!--
                     --><div style="width:calc(50% - 10px);" class="hSplit_column_block box_element_block">
                            <h1>範例輸出</h1>
                            <textarea class="question_text_textarea" readonly><?php echo $qSample[1]; ?></textarea>
                        </div>
                    </div>
                    <div class="box_element_block">
                        <h1>解題導引</h1>
                        <p class="box_content_block"><?php echo $qHint; ?></p>
                    </div>
                    <input class="form_button form_button_withIcon" style="background-image:url('image/icon_uploadCode.png');" type="button" value="上傳程式碼" onclick="window.location.href='uploadCode.php?questionId=<?php echo $_GET['questionId']; ?>';" />
                    <input class="form_button form_button_withIcon" style="background-image:url('image/icon_viewCode.png');" type="button" value="查看程式碼" onclick="window.location.href='listCode.php?questionId=<?php echo $_GET['questionId']; ?>';" />
                </div>
                
            </div>

            <!-- footer. -->
            <?php require('../common/php/footer.php'); ?>

        </div>

    </body>
</html>

