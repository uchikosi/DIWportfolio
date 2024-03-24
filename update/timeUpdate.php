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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // POSTリクエストからデータを受け取る
    $id = $_POST['id'];
    $date = $_POST['date'];
    $category = $_POST['category'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $break_time = $_POST['break_time'];
    $over_time = $_POST['over_time'];

    // 実働時間を計算
    $start_time_seconds = strtotime($start_time);
    $end_time_seconds = strtotime($end_time);
    $break_time_seconds = strtotime($break_time) - strtotime('00:00:00');
    $working_seconds = $end_time_seconds - $start_time_seconds - $break_time_seconds;
    $working_hours = floor($working_seconds / 3600);
    $working_minutes = floor(($working_seconds % 3600) / 60);
    $standard_working_time = sprintf("%02d:%02d", $working_hours, $working_minutes);

    // SQLクエリの準備
    $sql = "UPDATE timeSheet SET date='$date', category='$category', start_time='$start_time', end_time='$end_time', break_time='$break_time', over_time='$over_time', standard_working_time='$standard_working_time' WHERE id=$id";

    // クエリを実行してデータを更新する
    if ($conn->query($sql) === TRUE) {
        echo "更新が正常に完了しました。";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }

    // データベース接続を閉じる
    $conn->close();
}

// 遷移前のページからIDを取得
$id = $_GET['id'];
var_dump($_SESSION);
// 更新フォームの表示
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // データベースから選択した行の情報を取得するクエリを実行するなどの処理を行う
    $sql = "SELECT * FROM timeSheet WHERE id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        ?>
        <form action="timeUpdate.php" method="POST" id="updateForm">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <label for="date">年月日:</label>
            <input type="date" name="date" id="date" value="<?php echo $row['date']; ?>">
            <br>

            <select name="category" id="category">
                <option value="公休" <?php if($row['category'] == '公休') echo 'selected'; ?>>公休</option>
                <option value="出勤" <?php if($row['category'] == '出勤') echo 'selected'; ?>>出勤</option>
                <option value="欠勤" <?php if($row['category'] == '欠勤') echo 'selected'; ?>>欠勤</option>
                <option value="有給" <?php if($row['category'] == '有給') echo 'selected'; ?>>有給</option>
                <option value="休日出勤" <?php if($row['category'] == '休日出勤') echo 'selected'; ?>>休日出勤</option>
                <option value="遅刻" <?php if($row['category'] == '遅刻') echo 'selected'; ?>>遅刻</option>
                <option value="早退" <?php if($row['category'] == '早退') echo 'selected'; ?>>早退</option>
            </select>
            <br>

            <label for="start_time">出勤時間:</label>
            <input type="text" id="start_time" name="start_time" value="<?php echo substr($row['start_time'], 0, 5); ?>" onchange="calculateWorkingTime()">
            <br>

            <label for="end_time">退勤時間:</label>
            <input type="text" id="end_time" name="end_time" value="<?php echo substr($row['end_time'], 0, 5); ?>" onchange="calculateWorkingTime()">
            <br>

            <label for="break_time">休憩時間:</label>
            <input type="text" id="break_time" name="break_time" value="<?php echo substr($row['break_time'], 0, 5); ?>" onchange="calculateWorkingTime()">
            <br>

            <label for="standard_working_time">実働時間:</label>
            <input type="text" id="standard_working_time" name="standard_working_time" value="<?php echo $row['standard_working_time']; ?>" readonly>
            <br>

            <label for="over_time">残業時間:</label>
            <input type="text" id="over_time" name="over_time" value="<?php echo substr($row['over_time'], 0, 5); ?>">
            <br>

            <input type="submit" value="更新">
        </form>
        <script>
            function calculateWorkingTime() {
                var startTime = document.getElementById("start_time").value;
                var endTime = document.getElementById("end_time").value;
                var breakTime = document.getElementById("break_time").value;

                startTime = new Date("2024-01-01 " + startTime);
                endTime = new Date("2024-01-01 " + endTime);
                breakTime = breakTime.split(":");
                breakTime = parseInt(breakTime[0]) * 3600 + parseInt(breakTime[1]) * 60;

                var workingSeconds = endTime.getTime() - startTime.getTime() - breakTime * 1000;
                var workingHours = Math.floor(workingSeconds / 3600000);
                var workingMinutes = Math.floor((workingSeconds % 3600000) / 60000);

                document.getElementById("standard_working_time").value = ("0" + workingHours).slice(-2) + ":" + ("0" + workingMinutes).slice(-2);
            }
        </script>
        <?php
    } else {
        echo "データが見つかりません";
    }
} else {
//     echo "IDが指定されていません";
}
?>
