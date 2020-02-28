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
    else if(!login_isAuth(priv_equalHigher, array(0, 1, 3)))
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
     *  Upload source code.
     *  Confirm is set to true: add source code to database.
     *  Confirm is NOT set to true: let user add source code (web page).
     */

    // Confirm is set to true.
    if(isGetValid('confirm') && $_GET['confirm']=='true')
    {
        // Get language and comment.
        if(isPostValid('uploadCode_field_language') && isset($_POST['uploadCode_field_comment']))
        {
            // Check language.
            if(is_numeric($_POST['uploadCode_field_language']) && (int)$_POST['uploadCode_field_language']>=0 && (int)$_POST['uploadCode_field_language']<=kMaxLanguage)
                $language = (int)$_POST['uploadCode_field_language'];
            else
                $language = false;

            // Check question id.
            if(is_numeric($_GET['questionId']) && $_GET['questionId']>0)
                $questionId = (int)$_GET['questionId'];
            else
                $questionId = false;

            // Cope comment.
            $comment = $_POST['uploadCode_field_comment'];

            // Upload source code.
            $code = false;

            if(isPostValid('uploadCode_field_sourceText_filename'))  // From text.
            {echo '123';
                $size = sizeof($_POST['uploadCode_field_sourceText_filename']);
                $code = '';
                
                for($i=0; $i<$size; ++$i)
                {
                    // Filename and content are not empty.
                    if($_POST['uploadCode_field_sourceText_filename'][$i]!='' && $_POST['uploadCode_field_sourceText_code'][$i]!='')
                        $code .= toEscape($_POST['uploadCode_field_sourceText_filename'][$i])."\n".toEscape($_POST['uploadCode_field_sourceText_code'][$i])."\n".'/0';
                }
            }
            
            if(isset($_FILES['uploadCode_field_sourceFile']))  // From file.
            {
                $size = sizeof($_FILES['uploadCode_field_sourceFile']['name']);
                $code = '';

                for($i=0; $i<$size; ++$i)
                {
                    // File is not empty.
                    if($_FILES['uploadCode_field_sourceFile']['name'][$i] != '')
                    {
                        $fileStream = fopen($_FILES['uploadCode_field_sourceFile']['tmp_name'][$i], 'r');
                        $code .= toEscape($_FILES['uploadCode_field_sourceFile']['name'][$i])."\n".toEscape(fread($fileStream, filesize($_FILES['uploadCode_field_sourceFile']['tmp_name'][$i])))."\n".'/0';
                        unlink($_FILES['uploadCode_field_sourceFile']['tmp_name'][$i]);
                    }
                }
            }

            if($language!==false && $questionId!==false && $comment!==false && $code!==false)
            {
                mysqlQuery('INSERT INTO `code` (`code`, `language`, `questionId`, `uploader`, `uploadTime`, `comment`) VALUES (\''.$code.'\', '.$language.', '.$questionId.', \''.$_SESSION['login_username'].'\', NOW(), \''.$comment.'\');');
                header('Location: listCode.php?questionId='.$questionId);
                exit(0);
            }
        }
    }

    /*
     *  Retrieve question title.
     */

    $dbRetrieve = mysqlQuery('SELECT `title` FROM `question_info` WHERE `id`='.$_GET['questionId'].' LIMIT 1;');

    if(mysqli_num_rows($dbRetrieve) > 0)  // Question id exists.
        $questionTitle = mysqli_fetch_row($dbRetrieve)[0];
    else  // Question id doesn't exist.
    {
        header('Location: index.php');
        exit(0);
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>上傳程式碼 - CodePeak</title>
        <link rel="shortcut icon" type="image/png" href="../common/image/icon.png" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Supermercado+One&display=swap" />
        <link rel="stylesheet" href="../common/css/common.css" />
        <link rel="stylesheet" href="../common/css/hSplit.css" />
        <link rel="stylesheet" href="../common/css/infoBox.css" />
        <link rel="stylesheet" href="../common/css/list.css" />
        <link rel="stylesheet" href="../common/css/form.css" />
        <link rel="stylesheet" href="../common/css/switch.css" />
        <link rel="stylesheet" href="../login/css/loginForm.css" />
        <link rel="stylesheet" href="css/uploadCode.css" />
        <script src="../common/js/jquery.js"></script>
        <script src="js/uploadCode.js"></script>
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
                    <h1>上傳程式碼：[<?php echo $_GET['questionId']; ?>] <?php echo $questionTitle ?></h1>
                    <ul class="switch_menu_block">
                        <li id="switch_item_fromText">直接貼上</li>
                        <li id="switch_item_fromFile">檔案上傳</li>
                    </ul>
                    <form class="form_block" action="uploadCode.php?questionId=<?php echo $_GET['questionId'] ?>&confirm=true" method="post" enctype="multipart/form-data">
                        <div class="form_element_container">
                            <label>程式語言：</label>
                            <select name="uploadCode_field_language">
                                <option value="0">C</option>
                                <option value="1">C++ 11</option>
                                <option value="2">C++ 14</option>
                                <option value="3">Java 1.6</option>
                                <option value="4">Java 1.7</option>
                                <option value="5">Java 1.8</option>
                                <option value="6">Python 2</option>
                                <option value="7">Python 3</option>
                            </select>
                        </div>
                        <div id="uploadCode_sourceText" class="form_element_container">
                            <p>貼上原始碼：</p>
                            <ol id="uploadCode_list_sourceText">
                                <li>
                                    <input style="margin:0px;" class="form_textbox" name="uploadCode_field_sourceText_filename[]" type="text" value="" placeholder="檔案名稱" required />
                                    <textarea class="form_textarea" name="uploadCode_field_sourceText_code[]" placeholder="程式原始碼" required></textarea>
                                </li>
                            </ol>
                            <input id="uploadCode_button_addSourceText" class="form_button" type="button" value="新增檔案" />
                        </div>
                        <div id="uploadCode_sourceFile" class="form_element_container">
                            <p>上傳原始碼：</p>
                            <ol id="uploadCode_list_sourceFile">
                                <li><input name="uploadCode_field_sourceFile[]" type="file" required /></li>
                            </ol>
                            <input id="uploadCode_button_addSourceFile" class="form_button" type="button" value="新增檔案" />
                        </div>
                        <div class="form_element_container"><textarea class="form_textarea" name="uploadCode_field_comment" placeholder="備註"></textarea></div>
                        <input style="background-image:url('../common/image/icon_confirm_yes.png');" class="form_button form_button_withIcon" type="submit" value="上傳" />
                        <input style="background-image:url('../common/image/icon_confirm_no.png');" class="form_button form_button_withIcon" type="button" value="取消" onclick="window.location.href='detail.php?questionId=<?php echo $_GET['questionId']; ?>';" />
                    </form>
                </div>

            </div>

            <!-- footer. -->
            <?php require('../common/php/footer.php'); ?>

        </div>

    </body>
</html>

