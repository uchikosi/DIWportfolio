<?php
// POSTリクエストを受け取る
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // フォームから送信されたデータを取得
    $month = $_POST["month"];
    $day = $_POST["day"];
    $categoryValue = $_POST["category"];
    // カテゴリーに応じて値を設定する
$category = ($categoryValue == 'holiday') ? '公休' :
            ($categoryValue == 'going_to_work') ? '出勤' :
            ($categoryValue == 'absence') ? '欠勤' :
            ($categoryValue == 'paid') ? '有給' :
            ($categoryValue == 'holiday_work') ? '休日出勤' :
            ($categoryValue == 'behind_time') ? '遅刻' :
            ($categoryValue == 'leaving_early') ? '早退' :
            '不明';

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
$sql = "INSERT INTO timeSheet (user_id,month, day, category, start_time, end_time, break_time, standard_working_time, over_time, remarks)
        VALUES ('13','$month', '$day', '$category', '$start_time', '$end_time', '$break_time', '$standard_working_time', '$over_time', '')";


    // クエリを実行してデータを挿入
    if ($conn->query($sql) === TRUE) {
        echo "レコードが正常に挿入されました";
    } else {
        echo "エラー: " . $sql . "<br>" . $conn->error;
    }

    // 接続を閉じる
    $conn->close();
}
?>
