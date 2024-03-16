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
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<script type="text/javascript" src="../js/time.js"></script>
</body>
</html>
