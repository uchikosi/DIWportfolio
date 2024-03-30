<?php
session_start();
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
<link rel="stylesheet" type="text/css" href="../css/common.css">
<title>Date Calendar</title>

<style>
    .attendance-cell {
      margin-right: 20px;
    }
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
    <!-- 登録 -->
    <h2>勤怠入力</h2>
    <div>
      <form action="timeRegisterComplete.php" method="post" id="attendanceForm">

        <label>年月日:</label>
        <input type="date" min="2024-01" name="date" id="selectedDate" value="">
        <!-- <br> -->

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
        <input type="time" id="start_time" name="start_time" onchange="calculateWorkTime()">
        <!-- <br> -->

        <label for="end_time" id="endTimeLabel">退勤時間:</label>
        <input type="time" id="end_time" name="end_time" onchange="calculateWorkTime()"><br>

        <label for="break_time" id="breakTimeLabel">休憩時間:</label>
        <input type="time" id="break_time" name="break_time" onchange="calculateWorkTime()">
        <!-- <br> -->

        <label for="standard_working_time" id="standardWorkingTimeLabel">実働時間:</label>
        <input type="time" id="standard_working_time" name="standard_working_time" readonly><br>
        <label for="over_time" id="overTimeLabel">残業時間:</label>
        <input type="time" id="over_time" name="over_time">
        <!-- <br> -->

        <input type="submit" value="送信">
      </form>
      <p><a href="../top.php">TOPへ戻る</a></p>
    </div>
    <?php
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
    }?>
  </main>

    <!-- 表示 -->
  <?php
  // 各区分のカウントを初期化
  $attendance_count = 0;
  $late_count = 0;
  $early_leave_count = 0;
  $holiday_work_count = 0;

  // 分を計算する関数
  function convertToMinutes($time) {
    list($hours, $minutes) = explode(':', $time);
    return $hours * 60 + $minutes;
  }

  if ($result) {
    foreach ($result as $row) {
      // 区分ごとにカウントを増やす
      switch ($row['category']) {
        case '出勤':
          $attendance_count++;
          break;
        case '遅刻':
          $late_count++;
          break;
        case '早退':
          $early_leave_count++;
          break;
        case '休日出勤':
          $holiday_work_count++;
          break;
        default:
          // 何もしない
          break;
      }
    }
  }

  // 出勤日数の合計
  $total_attendance_days = $attendance_count + $late_count + $early_leave_count + $holiday_work_count;

  // 合計値を初期化
  $total_working_time_minutes = 0;
  $total_overtime_minutes = 0;

  // データが存在するかどうかを確認
  if ($result) {
    foreach ($result as $row) {
      // 実働時間と残業時間を分単位に変換して合計に追加
      $total_working_time_minutes += convertToMinutes($row['standard_working_time']);
      $total_overtime_minutes += convertToMinutes($row['over_time']);

      // ここに他の処理を記述
    }
  }
  // 分を時間と分に変換する関数
  function convertToHoursAndMinutes($minutes) {
    $hours = floor($minutes / 60);
    $remaining_minutes = $minutes % 60;
    return sprintf('%02d:%02d', $hours, $remaining_minutes);
  }

  // 合計値のフォーマット
  $total_working_time_formatted = convertToHoursAndMinutes($total_working_time_minutes);
  $total_overtime_formatted = convertToHoursAndMinutes($total_overtime_minutes);
  // 合計残業時間の値に基づいてスタイルを適用するための条件
  $overtime_hours = floor($total_overtime_minutes / 60);
  $overtime_minutes = $total_overtime_minutes % 60;
  if ($overtime_hours > 20 || ($overtime_hours == 20 && $overtime_minutes > 0)) {
    $overtime_style = 'background-color: red;'; // 赤色
  } elseif ($overtime_hours > 10 || ($overtime_hours == 10 && $overtime_minutes > 0)) {
    $overtime_style = 'background-color: yellow;';// 黄色
  } else {
    $overtime_style = ''; // デフォルトのスタイル
  }
  ?>
  <div id="list">
    <div id=YearAndMonth>
      <?php
      // 現在の年と月を取得
      $current_year = date('Y');
      $current_month = date('m');
      ?>
      <h3><?php echo $current_year; ?>年</h3>
      <h3><?php echo $current_month; ?>月</h3>
    </div>
    <table>
      <tr>
        <th>月/日</th>
        <th>曜日</th>
        <th>区分</th>
        <th>出勤時間</th>
        <th>退勤時間</th>
        <th>休憩時間</th>
        <th>実働時間</th>
        <th>残業時間</th>
        <th>登録日時</th>
      </tr>
      <?php
      // 時間のフォーマットを整形する関数
      function formatTime($time) {
        return date('H:i', strtotime($time));
      }

      if ($result) {
        foreach ($result as $row) {
          $day = date('m/d', strtotime($row['date']));
          $date = strtotime($row['date']); // 日付を取得
          $day_of_week = date('w', $date); // 曜日を取得 (0: 日曜日, 1: 月曜日, ..., 6: 土曜日)
          $day_of_week_name = ['日', '月', '火', '水', '木', '金', '土'][$day_of_week]; // 曜日名

          $day = date('m/d', $date);

          $registered_time = date('m/d H:i', strtotime($row['registered_time']));
          echo "<tr>";
          echo "<td>{$day}</td>";
          echo "<td>{$day_of_week_name}</td>";
          echo "<td>{$row['category']}</td>";
          echo "<td>" . formatTime($row['start_time']) . "</td>"; // 出勤時間を整形して表示
          echo "<td>" . formatTime($row['end_time']) . "</td>"; // 退勤時間を整形して表示
          echo "<td>" . formatTime($row['break_time']) . "</td>"; // 休憩時間を整形して表示
          echo "<td>" . formatTime($row['standard_working_time']) . "</td>"; // 実働時間を整形して表示
          echo "<td>" . formatTime($row['over_time']) . "</td>"; // 残業時間を整形して表示
          echo "<td>{$registered_time}</td>"; // 登録日時をフォーマットしたものを表示
          echo "</tr>";
        }
        // 合計行
        echo "<tr>";
        echo "<td colspan='3'><strong>合計</strong></td>";
        echo "<td colspan='3'><strong class=\"attendance-cell\">出勤日数</strong>{$total_attendance_days}<strong>日</strong></td>"; // 出勤日数の合計
        echo "<td><strong>{$total_working_time_formatted}</strong></td>";
        echo "<td style='{$overtime_style}'><strong>{$total_overtime_formatted}</strong></td>"; // スタイルを適用
        echo "<td></td>"; // 登録日時の列は空白にする
        echo "</tr>";
      } else {
        echo "<tr><td colspan='9'>現在該当するデータがありません。</td></tr>";
      }
      ?>
    </table>
  </div>
  <footer>
    Copytifht  is the one which provides A to Z about programming
  </footer>
  <script type="text/javascript" src="../js/time.js"></script>
</body>
</html>
