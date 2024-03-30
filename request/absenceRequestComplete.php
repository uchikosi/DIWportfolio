<?php

// try ブロック:データベースへの登録処理が try内で実行されます。この部分でエラーが発生した場合は、catch ブロックに処理が移ります。
try {
  $pdo = new PDO("mysql:dbname=AttendanceManagement;host=localhost;", "root", "root");
  // prepare()メソッドは、実行するSQLクエリのプリペアドステートメント（準備された文）を作成します。VALUES以下の各?はプレースホルダであり、後でバインドされる値が入る場所を表しています。
  $stmt = $pdo->prepare("INSERT INTO holidayRequest (name, name_kana, staff_code, request_date_start, request_date_end, category, remarks) VALUES (?, ?, ?, ?, ?, ?, ?)");

  // execute()メソッドは、プリペアドステートメントを実行します。配列内の値が対応するプレースホルダにバインドされます。実行結果は$resultに格納されます。
  $result = $stmt->execute([
    $_POST['name'],
    $_POST['name_kana'],
    $_POST['staff_code'],
    $_POST['request_date_start'],
    $_POST['request_date_end'],
    $_POST['category'],
    $remarks = $_POST['remarks'],
  ]);

  if ($result) {
    // データベースへの登録が成功した場合に実行される。$result が true の場合メッセージが出力されます。
    // echo "データベースへの登録が完了しました。";
    $success = "連絡内容を送信しました。";
  } else {
    // エラーが発生した場合の処理
    $errorInfo = $stmt->errorInfo(); // エラー情報を取得
    if ($errorInfo[0] !== "00000") {
      // エラーメッセージを表示
      echo "エラーコード: " . $errorInfo[0] . "<br>";
      echo "SQLSTATE: " . $errorInfo[1] . "<br>";
      echo "エラーメッセージ: " . $errorInfo[2] . "<br>";
    }
    $failure = "エラーが発生したため受付できませんでした。";
  }
} catch (PDOException $e) {
  // エラーが発生した場合の処理
  echo "データベースへの登録が失敗しました。";
}

session_start();
  // もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: login.php");
    exit();
  }

  // ユーザーの権限を取得
  $role = $_SESSION['role'] ?? null;
  $user_id = $_SESSION['user_id'] ?? null; // ユーザーIDを取得
  $family_name = $_SESSION['family_name'] ?? null;
  $last_name = $_SESSION['last_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>欠勤、早退、遅刻、連絡完了画面</title>
  <style>
  </style>
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
  <main>
    <div>
      <?php
        if (isset($success)) {
          echo $success;// 成功メッセージ
          echo "<br>";
        }

        if (isset($failure)) {
          echo $failure;// 失敗メッセージ
          echo "<br>";
        }
    ?>
    </div>

    <div>
      <p>
        <a href="http://localhost:8888/AttendanceManagementSystem/top.php" id="topBack" >TOPページへ戻る</a>
      </p>
      <p>
        <a href="http://localhost:8888/AttendanceManagementSystem/report/reportLog.php">申請のログへ</a>
      </p>
    </div>
  </main>
  <footer>
    <p>Copytifht the one which provides A to Z about programming</p>
  </footer>
</body>
</html>
