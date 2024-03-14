<?php
// セッションを開始または再開します
session_start();

// セッションを破棄してログアウトします
session_unset();
session_destroy();

// ログインページにリダイレクトします
header("Location: login.php");
exit();
?>
