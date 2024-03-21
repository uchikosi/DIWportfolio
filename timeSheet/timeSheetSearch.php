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

// 遷移前のページからユーザーIDを取得
if(isset($_GET['user_id'])) {
    $_SESSION['id'] = $_GET['user_id'];
}
// echo "取得したID: " . $user_id;
// var_dump($_SESSION);

// ユーザーIDがセッションにない場合はエラーを表示して終了
if(!isset($_SESSION['id'])) {
    die("ユーザーIDがセットされていません");
}

?>
<!-- 検索フォーム -->
<form action="timeSheetSearch.php" method="GET">
    <label for="year_month">年月:</label>
    <input type="month" id="year_month" name="year_month" value="<?php echo isset($_GET['year_month']) ? $_GET['year_month'] : date('Y-m'); ?>">

    <button type="submit">検索</button>
</form>

<?php
// 勤怠情報の検索クエリの作成
$sql = "SELECT * FROM timeSheet WHERE user_id = {$_SESSION['id']}";

// 追加された検索条件（年月）
if(isset($_GET['year_month']) && !empty($_GET['year_month'])) {
    $year_month = $_GET['year_month'];
    $sql .= " AND DATE_FORMAT(date, '%Y-%m') = '$year_month'";
}

// 検索結果の取得
$result = $conn->query($sql);

// 検索結果の表示
if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr><th>年</th><th>月</th><th>日</th><th>区分</th><th>残業時間</th></tr>";
    while($row = $result->fetch_assoc()) {
        $date = date_create($row["date"]);
        echo "<tr>";
        echo "<td>".date_format($date, "Y")."</td>";
        echo "<td>".date_format($date, "n")."</td>";
        echo "<td>".date_format($date, "j")."</td>";
        echo "<td>".$row["category"]."</td>";
        echo "<td>".$row["over_time"]."</td>"; // カラム名を「over_time」に修正
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "該当する勤怠情報がありません";
}

$conn->close();
?>
