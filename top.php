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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/top.css">
  <title>TOP</title>
</head>
<body>
  <header>
    <h1>勤怠報告</h1>
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
        <a href="http://localhost:8888/AttendanceManagementSystem/timeRegister/timeRegister.php">勤務入力</a>
      </li>
      <li>
        <a href="http://localhost:8888/AttendanceManagementSystem/userSearch/registrationInformation.php">登録情報</a>
      </li>
      <li>
        <a href="http://localhost:8888/AttendanceManagementSystem/timeSheet/timeSheet.php">タイムシート</a>
      </li>
      <li>
        <a href="http://localhost:8888/AttendanceManagementSystem/request/request.php">申請</a>
      </li>
      </div>
      <div id=administrator>
        <?php if ($role === '管理者'): ?>
        <li>
          <a href="http://localhost:8888/AttendanceManagementSystem/userRegister/userRegister.php">従業員登録</a>
        </li>
        <li>
          <a href="http://localhost:8888/AttendanceManagementSystem/userSearch/userSearch.php">検索</a>
        </li>
        <li>
          <a href="http://localhost:8888/AttendanceManagementSystem/report/report.php">報告、申請一覧</a>
        </li>
        <?php endif; ?>
      </div>
    </ul>
  </main>
  <footer>
Copytifht  is the one which provides A to Z about programming
  </footer>
</body>
</html>
