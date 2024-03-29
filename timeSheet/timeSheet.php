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

// ユーザーデータ取得
if (isset($user_id)) {

    // ユーザーデータを取得するクエリを実行
    $stmt = $conn->prepare("SELECT * FROM timeSheet WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        die("ユーザーデータが見つかりません");
    }
} else {
    die("ユーザーIDがセットされていません");
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
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
  <!-- 検索フォーム -->
  <form action="timeSheet.php" method="GET">
    <label for="year_month">年月:</label>
    <input type="month" id="year_month" name="year_month" value="<?php echo isset($_GET['year_month']) ? $_GET['year_month'] : date('Y-m'); ?>">

    <label for="day">日:</label>
    <select id="day" name="day">
      <option value=""selected disabled>選択してください</option>
      <option value="全て" <?php if(isset($_GET['day']) && $_GET['day'] == "全て") echo "selected"; ?>>全て</option>
      <?php for($i = 1; $i <= 31; $i++): ?>
        <option value="<?php echo $i; ?>" <?php if(isset($_GET['day']) && $_GET['day'] == $i) echo "selected"; ?>><?php echo $i; ?></option>
      <?php endfor; ?>
    </select>

    <label for="category">区分:</label>
    <select id="category" name="category">
      <option value="" <?php if(!isset($_GET['category'])) echo "selected disabled"; ?>>選択してください</option>
      <option value="全て" <?php if(isset($_GET['category']) && $_GET['category'] == "全て") echo "selected"; ?>>全て</option>
      <option value="公休" <?php if(isset($_GET['category']) && $_GET['category'] == "公休") echo "selected"; ?>>公休</option>
      <option value="出勤" <?php if(isset($_GET['category']) && $_GET['category'] == "出勤") echo "selected"; ?>>出勤</option>
      <option value="欠勤" <?php if(isset($_GET['category']) && $_GET['category'] == "欠勤") echo "selected"; ?>>欠勤</option>
      <option value="有給" <?php if(isset($_GET['category']) && $_GET['category'] == "有給") echo "selected"; ?>>有給</option>
      <option value="休日出勤" <?php if(isset($_GET['category']) && $_GET['category'] == "休日出勤") echo "selected"; ?>>休日出勤</option>
      <option value="遅刻" <?php if(isset($_GET['category']) && $_GET['category'] == "遅刻") echo "selected"; ?>>遅刻</option>
      <option value="早退" <?php if(isset($_GET['category']) && $_GET['category'] == "早退") echo "selected"; ?>>早退</option>
    </select>

    <label for="overtime">残業時間:</label>
    <select id="overtime" name="overtime">
      <option value="" <?php if(!isset($_GET['overtime'])) echo "selected disabled"; ?>>選択してください</option>
      <option value="全て" <?php if(isset($_GET['overtime']) && $_GET['overtime'] == "全て") echo "selected"; ?>>全て</option>
      <option value="あり" <?php if(isset($_GET['overtime']) && $_GET['overtime'] == "あり") echo "selected"; ?>>あり</option>
      <option value="なし" <?php if(isset($_GET['overtime']) && $_GET['overtime'] == "なし") echo "selected"; ?>>なし</option>
    </select>

    <button type="submit">検索</button>
  </form>

  <?php
  // 検索フォームの送信処理
  if(isset($_GET['year_month']) || isset($_GET['day']) || isset($_GET['category']) || isset($_GET['overtime'])){
    // 検索条件が送信された場合の処理

    // 勤怠情報の検索クエリの作成
    $sql = "SELECT * FROM timeSheet WHERE user_id = $user_id";

    // 検索条件（年月）
    if(isset($_GET['year_month']) && !empty($_GET['year_month'])) {
      $year_month = $_GET['year_month'];
      $sql .= " AND DATE_FORMAT(date, '%Y-%m') = '$year_month'";
    }

    // 検索条件（日）
    if(isset($_GET['day']) && !empty($_GET['day'])){
      $day = $_GET['day'];
      if ($day !== "全て") {
        $sql .= " AND DAY(date) = $day";
      }
    }

    // 検索条件（区分）
    if(isset($_GET['category']) && !empty($_GET['category'])){
      $category = $_GET['category'];
      if ($category !== "全て") {
        $sql .= " AND category = '$category'";
      }
    }

    // 検索条件（残業時間）
    if(isset($_GET['overtime']) && !empty($_GET['overtime'])) {
      $overtime = $_GET['overtime'];
      if ($overtime !== "全て") {
        if ($overtime == "あり") {
          $sql .= " AND over_time != '00:00:00'";
        } else if ($overtime == "なし") {
          $sql .= " AND over_time = '00:00:00'";
        }
      }
    }

    // 検索結果の取得
    $result = $conn->query($sql);
    // 検索結果がない場合はメッセージを表示しないようにする
    if ($result->num_rows === 0) {
        $no_result_message = true;
    }
  }

  // 検索フォームの送信処理後に表を表示する
  if(isset($result) && (isset($_GET['year_month']) || isset($_GET['day']) || isset($_GET['category']) || isset($_GET['overtime']))) {
    if($result->num_rows > 0) {
      // 検索結果の表示
      echo "<h3>".date_format(date_create($year_month), "Y年n月")."</h3>";
      echo "<table border='1'>";
      echo "<tr><th>日</th><th>区分</th><th>出勤時間</th><th>退勤時間</th><th>休憩時間</th><th>実働時間</th><th>残業時間</th></tr>";
      $timeid = $row['id'];

      while($row = $result->fetch_assoc()) {
        $date = date_create($row["date"]);
        echo "<tr>";
        echo "<td>".date_format($date, "j")."</td>";
        echo "<td>".$row["category"]."</td>";
        echo "<td>".substr($row["start_time"], 0, 5)."</td>";
        echo "<td>".substr($row["end_time"], 0, 5)."</td>";
        echo "<td>".substr($row["break_time"], 0, 5)."</td>";
        echo "<td>".substr($row["standard_working_time"], 0, 5)."</td>";
        echo "<td>".substr($row["over_time"], 0, 5)."</td>";

        // 残業時間と実働時間の合計を計算
        $total_overtime += strtotime($row["over_time"]);
        $total_working_time += strtotime($row["standard_working_time"]);

        // 区分ごとのカウント
        switch ($row["category"]) {
          case '出勤':
            $count_attendance++;
            break;
          case '遅刻':
            $count_late++;
            break;
          case '早退':
            $count_early_leave++;
            break;
          case '休日出勤':
            $count_holiday_work++;
            break;
          default:
            break;
        }
      }

      // 出勤日数を加算
      $total_attendance = $count_attendance + $count_late + $count_early_leave + $count_holiday_work;

      // 合計行を表示
      echo "<tr>";
      echo "<td colspan='2'>合計</td>";
      echo "<td colspan='3'>出勤日数$total_attendance 日</td>";
      echo "<td>".substr(gmdate("H:i:s",$total_working_time), 0, 5)."</td>";
      echo "<td>".substr(gmdate("H:i:s",$total_overtime), 0, 5)."</td>";
      echo "</tr>";
      echo "</table>";
    } elseif (isset($no_result_message)) {
      echo "該当する勤怠情報がありません";
    }
  } elseif (!isset($_GET['year_month']) && !isset($_GET['day']) && !isset($_GET['category']) && !isset($_GET['overtime'])) {
    // 検索条件が送信されていない場合の処理
    echo "確認したい条件を指定してください。";
  }
  $conn->close();
  ?>
</main>
</body>
</html>
