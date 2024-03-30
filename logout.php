<?php
// セッションを開始または再開します
session_start();

// セッション変数を破棄してログアウトします
session_unset(); // セッション変数をクリア
session_destroy(); // セッションを破棄

// ログインページにリダイレクトします
header("Location: login.php");
exit();
?>
