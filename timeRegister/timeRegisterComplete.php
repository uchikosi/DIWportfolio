<?php
session_start();
// ユーザーの権限を取得
$role = $_SESSION['role'] ?? null;
$user_id = $_SESSION['user_id'] ?? null;

// ユーザーIDを取得
$user_id = $_SESSION['user_id'];

// POSTリクエストを受け取る
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // フォームから送信されたデータを取得
  $date = $_POST["date"];
  $categoryValue = $_POST["category"];

// カテゴリーに応じて値を設定する
switch ($categoryValue) {
  case 'holiday':
    $category = '公休';
    break;
  case 'going_to_work':
    $category = '出勤';
    break;
  case 'absence':
    $category = '欠勤';
    break;
  case 'paid':
    $category = '有給';
    break;
  case 'holiday_work':
    $category = '休日出勤';
    break;
  case 'behind_time':
    $category = '遅刻';
    break;
  case 'leaving_early':
    $category = '早退';
    break;
  default:
    $category = '不明';
}

$start_time = $_POST["start_time"];
$end_time = $_POST["end_time"];
$break_time = $_POST["break_time"];
$standard_working_time = $_POST["standard_working_time"];
$over_time = $_POST["over_time"];

// データベースに接続
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "AttendanceManagement";
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続を確認
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

var_dump($category);

// データをデータベースに挿入するSQLクエリを作成
$sql = "INSERT INTO timeSheet (user_id,date, category, start_time, end_time, break_time, standard_working_time, over_time, remarks)
VALUES ('$user_id','$date', '$category', '$start_time', '$end_time', '$break_time', '$standard_working_time', '$over_time', '')";


    // クエリを実行してデータを挿入
if ($conn->query($sql) === TRUE) {
  echo "レコードが正常に挿入されました";
  // 挿入が成功した場合
  header("Location: timeRegister.php?message=success");
  exit();
} else {
  echo "エラー: " . $sql . "<br>" . $conn->error;
  // エラーが発生した場合
  header("Location: timeRegister.php?message=error");
  exit();
}
  // 接続を閉じる
  $conn->close();
}
?>
