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
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>TOP</title>
</head>
<body>
  <header>
    <ul id="menu">
      <h1 id=mainTitole>勤怠アプリ</h1>
      <div class="nav">
        <li class="nav_list">ようこそ <?php echo $family_name.$last_name ; ?>様</li>
        <li class="nav_list"> <?php echo $_SESSION['mail']; ?></li>
      </div>
      <?php if ($role === '管理者'): ?>
      <li class="supervisor">アカウント権限 管理者</li>
      <?php endif; ?>
      <li class="nav"><a href="../logout.php" id="logout">Logout</a></li>
    </ul>
  </header>
  <h1>申請</h1>
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
        <a href="http://localhost:8888/AttendanceManagementSystem/report/reportLog.php">申請のログ</a>
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
