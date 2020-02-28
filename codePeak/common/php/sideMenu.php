<!--
    Common info box for side menu.

    Note:
        To make the content show corrrectly,
        please link css file('/common/css/infoBox.css', '/common/css/list.css', '/login/css/loginForm.css') in parent file also.

        This file is able to be included by '/*/*' to work correctly.
-->

<?php require_once('../login/index.php'); ?>

<div class="infoBox_block">
    <h1 class="infoBox_title_text">功能選單 Menu</h1>
    <div class="infoBox_content_block">
        <ul class="unorderedList_explicit_block">
            <li><a href="../bulletin" target="_self">公告</a></li>
            <li><a href="../question" target="_self">題庫</a></li>
            <li><a href="../question/listCode.php" target="_self">我的程式碼</a></li>
        </ul>
    </div>
</div>

<div class="spacer_tiny_block"></div>

<div class="infoBox_block">
    <h1 class="infoBox_title_text"><?php echo isLogin()?'使用者 User':'登入 Login'; ?></h1>
    <div class="infoBox_content_block">
        <?php

            if(isLogin())  // Logged in.
            {
                if(isGetValid('loginStatus') && is_numeric($_GET['loginStatus']) && (int)$_GET['loginStatus']==6)
                    echo '<div class="loginForm_errorMessage_text">'.loginStatusToString($_GET['loginStatus']).'</div>';

                echo '
                    <ul class="unorderedList_implicit_block">
                        <li>帳號：'.$_SESSION['login_username'].'</li>
                        <li>暱稱：'.$_SESSION['login_nickname'].'</li>
                        <li>權限：'.privToString($_SESSION['login_privilege']).'</li>
                    </ul>
                    <input class="loginForm_button_submit" type="button" value="登出" onclick="window.location.href=\'../login/logout.php\';" />
                '."\n";
            }
            else  // Not logged in.
            {
                if(isGetValid('loginStatus'))
                    echo '<div class="loginForm_errorMessage_text">'.loginStatusToString($_GET['loginStatus']).'</div>';

                echo '
                    <form class="loginForm_form" action="../login/check.php" method="post">
                        <input style="background-image:url(\'../login/image/icon_login_username.png\');" class="loginForm_input_text loginForm_input_text_withIcon" name="loginForm_field_username" type="text" value="" placeholder="帳號 Username" required />
                        <input style="background-image:url(\'../login/image/icon_login_password.png\');" style="" class="loginForm_input_text loginForm_input_text_withIcon" name="loginForm_field_password" type="password" value="" placeholder="密碼 Password" required />
                        <input class="loginForm_button_submit" type="submit" value="登入" />
                    </form>
                '."\n";
            }
        ?>
    </div>
</div>

<div class="spacer_tiny_block"></div>

<div class="infoBox_block">
    <h1 class="infoBox_title_text">流量分析 Analysis</h1>
    <div class="infoBox_content_block">
        <ul class="unorderedList_implicit_block">
            <li>本日人次：</li>
            <li>本週人次：</li>
            <li>本月人次：</li>
            <li>今年人次：</li>
            <li>總人次：</li>
        </ul>
    </div>
</div>

<div class="spacer_tiny_block"></div>

<div class="infoBox_block">
    <h1 class="infoBox_title_text">關於本站 About</h1>
    <div class="infoBox_content_block">
        本站源於108.11.09，旨為提昇南大資工系學生的程式能力，並輔導學生參加ITSA競賽。
    </div>
</div>

<div class="spacer_tiny_block"></div>

<div class="infoBox_block">
    <h1 class="infoBox_title_text">聯絡我們 ContactUs</h1>
    <div class="infoBox_content_block">
        若有任何問題，請聯繫<a href="mailto:haward79@yahoo.com.tw" target="_blank">網頁管理員</a>。
    </div>
</div>

