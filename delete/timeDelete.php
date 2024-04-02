<?php
session_start();
// データベース接続
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'AttendanceManagement';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// 削除ボタンが押された場合
if(isset($_POST['delete'])) {
  // ユーザーが確認メッセージでOKを選択した場合のみ削除処理を実行
  $id = $_GET['id'];
  $sql = "DELETE FROM timeSheet WHERE id = $id";
  if ($conn->query($sql) === TRUE) {
    // 削除が完了したメッセージを表示
    echo "<script>alert('削除が完了しました');</script>";
    // 削除後の遷移先のURLにGETパラメータを追加して、user_idとuser_nameを渡す
    echo "<script>window.location.href = 'http://localhost:8888/AttendanceManagementSystem/timeSheet/timeSheetSearch.php?user_id={$_SESSION['id']}&user_name={$_SESSION['user_name']}';</script>";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
}
// var_dump($_SESSION);
if(isset($_GET['id'])) {
  // $_GET['id'] を使った処理
} else {
  echo "IDがセットされていません";
}

// 勤務情報の取得と表示
$id = $_GET['id'];
$sql = "SELECT * FROM timeSheet WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // データがある場合は表示する
  $row = $result->fetch_assoc();
  $date = $row['date'];
  $category = $row['category'];
  $start_time = $row['start_time'];
  $end_time = $row['end_time'];
  $break_time = $row['break_time'];
  $standard_working_time = $row['standard_working_time'];
  $over_time = $row['over_time'];
} else {
  // データがない場合はエラーメッセージを表示する
  echo "勤務情報が見つかりませんでした";
}

if(isset($_SESSION['user_name'])) {
  $user_name = $_SESSION['user_name'];
} else {
  // セッションにユーザー名がない場合はエラーメッセージを表示して終了
  die("ユーザー名がセットされていません");
}
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
  // var_dump($_SESSION);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/timeDelete.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>勤務情報削除</title>
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
    <!-- 勤務情報の表示 -->
    <h1 id="title">勤務情報削除</h1>
    <div id="YearAndMonth">
      <p><?php echo $user_name; ?>さんの勤怠情報</p>
      <!-- 年と月の表示 -->
      <p><?php echo date_format(date_create($year_month), "Y年n月"); ?></p>
    </div>

    <div id="information">
    <ul>
      <li><strong>日付：</strong><?php echo $date; ?></li>
      <li><strong>区分：</strong><?php echo $category; ?></li>
      <li><strong>出勤時間：</strong><?php echo $start_time; ?></li>
      <li><strong>退勤時間：</strong><?php echo $end_time; ?></li>
      <li><strong>休憩時間：</strong><?php echo $break_time; ?></li>
      <li><strong>実働時間：</strong><?php echo $standard_working_time; ?></li>
      <li><strong>残業時間：</strong><?php echo $over_time; ?></li>
    </ul>
    <!-- 削除ボタン -->
    <form id="deleteForm" method="post">
      <input type="submit" name="delete" value="削除">
    </form>
    </div>

    <div id="back">
      <p class="button">
        <a href="http://localhost:8888/AttendanceManagementSystem/top.php" id="topBack" >TOPページへ戻る</a>
      </p>
      <p class="button">
        <!-- 元のユーザーのtimeSheetSearch.phpに遷移するリンク -->
        <a href="../timeSheet/timeSheetSearch.php?user_id=<?php echo $_SESSION['id']; ?>&user_name=<?php echo $_SESSION['user_name']; ?>" >元のページに戻る</a>
      </p>
    </div>
  </main>
  <footer>Copytifht is the one which provides A to Z about programming</footer>
  <script>
    document.getElementById("deleteForm").addEventListener("submit", function(event) {
      var confirmMessage = "一度削除した勤怠情報は元に戻させません。\n";
      confirmMessage += "本当に削除してよろしいですか？\n";
      confirmMessage += "名前: <?php echo $user_name; ?>さん\n";
      confirmMessage += "日付:<?php echo $date; ?>の勤務情報\n";

      // 確認ダイアログを表示し、キャンセルの場合はフォームの送信をキャンセルする
      if (!confirm(confirmMessage)) {
        // キャンセルされた場合はフォームの送信をキャンセルする
        event.preventDefault();
      }
    });
  </script>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
<?php
// データベース接続を閉じる
$conn->close();
?>
