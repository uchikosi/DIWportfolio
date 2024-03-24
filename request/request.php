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
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/top.css">
  <title>TOP</title>
</head>
<body>
  <header>
    <h1>申請</h1>
    <div id="head">
      <p>ようこそ <?php echo $family_name.$last_name ; ?>様</p>
      <p> <?php echo $_SESSION['mail']; ?></p>
      <?php if ($role === '管理者'): ?>
        <p>このアカウント権限は管理者です</p>
      <?php endif; ?>
      <p><a href="logout.php">Logout</a></p>
    </div>
  </header>
  <main>
    <ul>
      <div id='general'>
      <li>
        <a href="http://localhost:8888/AttendanceManagementSystem/request/absenceRequest.php">欠勤、遅刻、早退 <br>申請</a>
      </li>
      <li>
        <a href="http://localhost:8888/AttendanceManagementSystem/request/holidayRequest.php">休日申請</a>
      </li>
      <li>
        <a href="http://localhost:8888/AttendanceManagementSystem/request/requestLog.php">申請のログ</a>
      </li>
      </div>
      </div>
    </ul>
  </main>
  <footer>
Copytifht  is the one which provides A to Z about programming
  </footer>
</body>
</html>
