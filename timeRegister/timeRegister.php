<?php
session_start();
// メッセージが渡されたかどうかを確認
if (isset($_GET['message'])) {
    // メッセージを取得
    $message = $_GET['message'];

    // メッセージに応じて表示を変更
    if ($message == "success") {
        echo "お疲れ様でした、勤怠情報が正常に送信されました。";
    } elseif ($message == "error") {
        echo "エラー：勤怠情報を送信できませんでした。";
    }
}

// データベースへの接続
mb_internal_encoding("utf8");
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "AttendanceManagement";

try {
    $pdo = new PDO("mysql:dbname={$dbname};host={$servername}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベースへの接続に失敗しました: " . $e->getMessage());
}

// ユーザーIDを取得
$user_id = $_SESSION['user_id'];
// 現在の月を取得
$current_month = date('Y-m');

// SQLクエリを作成
$sql = "SELECT * FROM timeSheet WHERE user_id = :user_id AND DATE_FORMAT(date, '%Y-%m') = :current_month ORDER BY date ASC";

try {
    // プリペアドステートメントを準備
    $stmt = $pdo->prepare($sql);
    // パラメータをバインド
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':current_month', $current_month, PDO::PARAM_STR);
    // クエリを実行
    $stmt->execute();

    // 結果を取得
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "クエリの実行中にエラーが発生しました: " . $e->getMessage();
}

// もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: ../login.php");
    exit();
  }

  // ユーザーの権限を取得
  $role = $_SESSION['role'] ?? null;
  $user_id = $_SESSION['user_id'] ?? null;
  $family_name = $_SESSION['family_name'] ?? null;
  $last_name = $_SESSION['last_name'] ?? null;
  var_dump($user_id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/css" href="../css/time.css">
<title>Date Calendar</title>
<!-- <link rel="stylesheet" href="styles.css"> -->
<style>
    /* ユーザーが直接入力できないようにする */
    .hidden {
        display: none;
    }
</style>
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
      <p><a href="../logout.php">Logout</a></p>
      <p><a href="../top.php">TOPへ戻る</a></p>
    </div>
  </header>

  <!-- 登録 -->
  <h2>勤怠入力</h2>
  <div>
    <form action="timeRegisterComplete.php" method="post" id="attendanceForm">

      <label>年月日:</label>
      <input type="date" min="2024-01" name="date" id="selectedDate" value="">
      <br>

      <label>区分:</label>
      <select name="category" id="categorySelect" onchange="handleCategoryChange()">
        <option value="" selected disabled>選択してください</option>
        <option value="holiday">公休</option>
        <option value="going_to_work">出勤</option>
        <option value="absence">欠勤</option>
        <option value="paid">有給</option>
        <option value="holiday_work">休日出勤</option>
        <option value="behind_time">遅刻</option>
        <option value="leaving_early">早退</option>
      </select><br>

      <label for="start_time" id="startTimeLabel">出勤時間:</label>
      <input type="time" id="start_time" name="start_time" onchange="calculateWorkTime()"><br>

      <label for="end_time" id="endTimeLabel">退勤時間:</label>
      <input type="time" id="end_time" name="end_time" onchange="calculateWorkTime()"><br>

      <label for="break_time" id="breakTimeLabel">休憩時間:</label>
      <input type="time" id="break_time" name="break_time" onchange="calculateWorkTime()"><br>

      <label for="standard_working_time" id="standardWorkingTimeLabel">実働時間:</label>
      <input type="time" id="standard_working_time" name="standard_working_time" readonly><br>
      <label for="over_time" id="overTimeLabel">残業時間:</label>
      <input type="time" id="over_time" name="over_time"><br>

      <input type="submit" value="送信">
    </form>
  </div>

  <!-- 表示 -->
  <?php
// 現在の年と月を取得
$current_year = date('Y');
$current_month = date('m');
?>

<div id="list">
    <div id=YearAndMonth>
      <h3><?php echo $current_year; ?>年</h3>
      <h3><?php echo $current_month; ?>月</h3>
    </div>
    <table>
      <tr>
        <th>日</th>
        <th>区分</th>
        <th>出勤時間</th>
        <th>退勤時間</th>
        <th>休憩時間</th>
        <th>実働時間</th>
        <th>残業時間</th>
        <th>登録日時</th>
      </tr>
      <?php
      if ($result) {
        foreach ($result as $row) {
          $day = date('d', strtotime($row['date']));
          // 登録日時のフォーマットを月、日、時、分だけに変更
          $registered_time = date('m/d H:i', strtotime($row['registered_time']));
          echo "<tr>";
          echo "<td>{$day}</td>";
          echo "<td>{$row['category']}</td>";
          echo "<td>{$row['start_time']}</td>";
          echo "<td>{$row['end_time']}</td>";
          echo "<td>{$row['break_time']}</td>";
          echo "<td>{$row['standard_working_time']}</td>";
          echo "<td>{$row['over_time']}</td>";
          echo "<td>{$registered_time}</td>"; // 登録日時をフォーマットしたものを表示
          echo "</tr>";
        }
      } else {
        echo "<tr><td colspan='9'>現在該当するデータがありません。</td></tr>";
      }
      ?>
  </table>
</div>
  <script type="text/javascript" src="../js/time.js"></script>
</body>
</html>
