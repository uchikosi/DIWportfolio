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
  // var_dump($_SESSION);
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/common.css">
  <link rel="stylesheet" type="text/css" href="css/top.css">
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
      <li class="nav"><a href="logout.php" id="logout">Logout</a></li>
    </ul>
  </header>
  <main>
    <ul>
      <div id='general'>
      <li class="topMenu">
        <a href="http://localhost:8888/AttendanceManagementSystem/timeRegister/timeRegister.php">勤務入力</a>
      </li>
      <li class="topMenu">
        <a href="http://localhost:8888/AttendanceManagementSystem/userSearch/registrationInformation.php">登録情報</a>
      </li>
      <li class="topMenu">
        <a href="http://localhost:8888/AttendanceManagementSystem/timeSheet/timeSheet.php">タイムシート</a>
      </li>
      <li class="topMenu">
        <a href="http://localhost:8888/AttendanceManagementSystem/request/request.php">申請、連絡メニュー</a>
      </li>
      </div>
      <div id=administrator>
        <?php if ($role === '管理者'): ?>
        <li class="topMenu">
          <a href="http://localhost:8888/AttendanceManagementSystem/userRegister/userRegister.php">従業員登録</a>
        </li>
        <li class="topMenu">
          <a href="http://localhost:8888/AttendanceManagementSystem/userSearch/userSearch.php">従業員検索</a>
        </li>
        <li class="topMenu">
          <a href="http://localhost:8888/AttendanceManagementSystem/report/reportList.php">報告、申請一覧</a>
        </li>
        <?php endif; ?>
      </div>
    </ul>
  </main>
  <footer>Copytifht  is the one which provides A to Z about programming</footer>
  <script type="text/javascript" src="js/common.js"></script>
</body>
</html>
