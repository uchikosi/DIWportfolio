<?php
  session_start();
  // セッションの有効期限を設定（1日）
  $expireAfter = 60 * 60 * 24; // 1日（秒数で指定）
  session_set_cookie_params($expireAfter);

  // もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: login.php");
    exit();
  } else {
    // ユーザーの権限を取得
    $role = $_SESSION['role'] ?? null;
    $user_id = $_SESSION['user_id'] ?? null; // ユーザーIDを取得
    $family_name = $_SESSION['family_name'] ?? null;
    $last_name = $_SESSION['last_name'] ?? null;
    $family_name_kana = $_SESSION['family_name_kana'] ?? null;
    $last_name_kana = $_SESSION['last_name_kana'] ?? null;
    var_dump($_SESSION);
  }

  // データベース接続
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'AttendanceManagement';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

  ?>

  <form action="registrationInformation.php" method="post">
  <label for="family_name">名前（姓）:</label><br>
  <input type="text" id="family_name" name="family_name"><br>

  <label for="last_name">名前（名）:</label><br>
  <input type="text" id="last_name" name="last_name"><br>

  <label for="family_name_kana">カナ（姓）:</label><br>
  <input type="text" id="family_name_kana" name="family_name_kana"><br>

  <label for="last_name_kana">カナ（名）:</label><br>
  <input type="text" id="last_name_kana" name="last_name_kana"><br>

  <label for="staff_code">スタッフコード:</label><br>
  <input type="text" id="staff_code" name="staff_code" readonly><br>

  <label for="mail">メールアドレス:</label><br>
  <input type="email" id="mail" name="mail"><br>

  <label for="password">パスワード:</label><br>
  <input type="password" id="password" name="password"><br>

  <label for="postal_code">郵便番号:</label><br>
  <input type="text" id="postal_code" name="postal_code"><br>

  <label for="address">住所:</label><br>
  <input type="text" id="address" name="address"><br>

  <input type="submit" value="更新する">
</form>
