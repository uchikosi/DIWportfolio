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
// 検索条件を取得
$search_year_month = isset($_GET['year_month']) ? $_GET['year_month'] : date('Y-m');
$search_day = isset($_GET['day']) ? $_GET['day'] : '';
$search_category = isset($_GET['category']) ? $_GET['category'] : '';
$search_overtime = isset($_GET['overtime']) ? $_GET['overtime'] : '';

// 検索フォームの送信処理
if (isset($_GET['year_month']) || isset($_GET['day']) || isset($_GET['category']) || isset($_GET['overtime'])) {
    // 検索条件が送信された場合の処理

    // 勤怠情報の検索クエリの作成
    $sql = "SELECT * FROM timeSheet WHERE user_id = $user_id";

    // 検索条件（年月）
    if (!empty($search_year_month)) {
        $sql .= " AND DATE_FORMAT(date, '%Y-%m') = '$search_year_month'";
    }

    // 検索条件（日）
    if (!empty($search_day)) {
        if ($search_day !== "全て") {
            $sql .= " AND DAY(date) = $search_day";
        }
    }

    // 検索条件（区分）
    if (!empty($search_category)) {
        if ($search_category !== "全て") {
            $sql .= " AND category = '$search_category'";
        }
    }

    // 検索条件（残業時間）
    if (!empty($search_overtime)) {
        if ($search_overtime !== "全て") {
            if ($search_overtime == "あり") {
                $sql .= " AND over_time != '00:00:00'";
            } else if ($search_overtime == "なし") {
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

// 検索結果がある場合のみ処理を実行
if ($result) {
    // 合計の初期化
    $total_working_time_minutes = 0;
    $total_overtime_minutes = 0;

    // 表のヘッダーを出力
    echo "<table border='1'>";
    echo "<tr><th>月/日</th><th>曜日</th><th>区分</th><th>出勤時間</th><th>退勤時間</th><th>休憩時間</th><th>実働時間</th><th>残業時間</th><th>登録日時</th></tr>";

    // 検索結果をループして行を出力
    foreach ($result as $row) {
        // 日付を取得
        $date = strtotime($row['date']);
        // 指定された年月に一致しない場合はスキップ
        if (date('Y-m', $date) !== $search_year_month) {
            continue;
        }

        // 区分ごとのカウントを増やす
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

        // 日付と曜日を表示
        echo "<tr>";
        echo "<td>" . date('m/d', $date) . "</td>";
        echo "<td>" . ['日', '月', '火', '水', '木', '金', '土'][date('w', $date)] . "</td>";
        echo "<td>{$row['category']}</td>";
        // 時間を表示
        echo "<td>" . date('H:i', strtotime($row['start_time'])) . "</td>";
        echo "<td>" . date('H:i', strtotime($row['end_time'])) . "</td>";
        echo "<td>" . date('H:i', strtotime($row['break_time'])) . "</td>";
        echo "<td>" . date('H:i', strtotime($row['standard_working_time'])) . "</td>";
        echo "<td>" . date('H:i', strtotime($row['over_time'])) . "</td>";
        // 登録日時を表示
        echo "<td>" . date('m/d H:i', strtotime($row['registered_time'])) . "</td>";
        echo "</tr>";

        // 実働時間と残業時間の合計を計算
        $total_working_time_minutes += convertToMinutes($row['standard_working_time']);
        $total_overtime_minutes += convertToMinutes($row['over_time']);
    }

    // 合計行を出力
    echo "<tr>";
    echo "<td colspan='3'><strong>合計</strong></td>";
    echo "<td colspan='3'><strong class=\"attendance-cell\">出勤日数</strong>" . ($attendance_count + $late_count + $early_leave_count + $holiday_work_count) . "<strong>日</strong></td>"; // 出勤日数の合計
    // 実働時間を2桁で表示
    echo "<td><strong>" . str_pad(floor($total_working_time_minutes / 60), 2, '0', STR_PAD_LEFT) . ":" . str_pad($total_working_time_minutes % 60, 2, '0', STR_PAD_LEFT) . "</strong></td>";

    // 残業時間を2桁で表示
    echo "<td><strong>" . str_pad(floor($total_overtime_minutes / 60), 2, '0', STR_PAD_LEFT) . ":" . str_pad($total_overtime_minutes % 60, 2, '0', STR_PAD_LEFT) . "</strong></td>"; // スタイルを適用
    echo "<td></td>"; // 登録日時の列は空白にする
    echo "</tr>";
    echo "</table>";
} else {
    // 検索結果がない場合のメッセージを出力
    echo "<p>現在、該当するデータがありません。</p>";
}
?>
</main>
<footer>
    Copytifht is the one which provides A to Z about programming
</footer>
</body>
</html>
