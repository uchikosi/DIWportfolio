<?php
session_start();

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
  <link rel="stylesheet" type="text/css" href="css/top.css">
  <title>TOP</title>
</head>
<body>
  <header>
    <div id="menu">
      <p>勤怠報告</p>
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
      <li>
        <a href="timeRegister/timeRegister.php">勤務入力</a>
      </li>
      <li>
        <a href="">勤務一覧</a>
      </li>
      <li>
        <a href="">登録情報</a>
      </li>
      <?php if ($role === '管理者'): ?>
        <li>
          <a href="userRegister/userRegister.php">従業員登録</a>
        </li>
        <li>
          <a href="">従業員検索</a>
        </li>
      <?php endif; ?>
    </ul>
  </main>
</body>
</html>
