<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Monthly Calendar</title>
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
    <label>月:</label>
    <input type="month" min="2024-01" name="month" id="selectedMonth" value="">
    <br>
    <span>日付</span>
    <select name="day" id="daySelect">
        <option value="" selected disabled>選択してください</option>
        <!-- JavaScriptで動的に日付を追加するための空のオプション -->
    </select><br>

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

<script>
    // 現在の日付を取得
    var currentDate = new Date();
    var currentYear = currentDate.getFullYear();
    var currentMonth = ('0' + (currentDate.getMonth() + 1)).slice(-2); // 0埋め
    var currentDateString = currentYear + '-' + currentMonth;

    // 現在の月をセット
    document.getElementById('selectedMonth').value = currentDateString;

    // 選択された月の日数を取得して日付選択フォームを更新
    updateDaySelect();

    // 月が変更された時の処理
    document.getElementById('selectedMonth').addEventListener('change', function() {
        updateDaySelect();
    });

    // 初期表示時に日付をセット
    document.getElementById('selectedMonth').dispatchEvent(new Event('change'));

    function updateDaySelect() {
        var selectedMonth = document.getElementById('selectedMonth').value;
        var date = new Date(selectedMonth + "-01");
        var daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

        var daySelect = document.getElementById('daySelect');
        daySelect.innerHTML = '';

        var defaultOption = document.createElement('option');
        defaultOption.value = "";
        defaultOption.textContent = "選択してください";
        defaultOption.selected = true;
        defaultOption.disabled = true;
        daySelect.appendChild(defaultOption);

        for (var i = 1; i <= daysInMonth; i++) {
            var option = document.createElement('option');
            option.value = i;
            option.textContent = i;
            daySelect.appendChild(option);
        }
    }

    function calculateWorkTime() {
        var startTime = document.getElementById('start_time').value;
        var endTime = document.getElementById('end_time').value;
        var breakTime = document.getElementById('break_time').value;

        if (startTime && endTime) {
            var startDateTime = new Date('2000-01-01T' + startTime);
            var endDateTime = new Date('2000-01-01T' + endTime);

            var totalWorkTime = endDateTime - startDateTime;

            if (breakTime) {
                var breakTimeParts = breakTime.split(":");
                var breakTimeInMilliseconds = (parseInt(breakTimeParts[0]) * 60 + parseInt(breakTimeParts[1])) * 60000;
                totalWorkTime -= breakTimeInMilliseconds;
            }

            var resultHours = Math.floor(totalWorkTime / (1000 * 60 * 60));
            var resultMinutes = Math.floor((totalWorkTime % (1000 * 60 * 60)) / (1000 * 60));
            if (!isNaN(resultHours) && !isNaN(resultMinutes)) {
                document.getElementById('standard_working_time').value = resultHours.toString().padStart(2, '0') + ":" + resultMinutes.toString().padStart(2, '0');
            }
        }
    }

  function handleCategoryChange() {
    var category = document.getElementById('categorySelect').value;

    if (category === "paid" || category === "holiday" || category === "absence") {
        // ラベルと入力フィールドを非表示にする
        document.getElementById('startTimeLabel').classList.add('hidden');
        document.getElementById('endTimeLabel').classList.add('hidden');
        document.getElementById('breakTimeLabel').classList.add('hidden');
        document.getElementById('standardWorkingTimeLabel').classList.add('hidden');
        document.getElementById('overTimeLabel').classList.add('hidden');
        document.getElementById('start_time').classList.add('hidden');
        document.getElementById('end_time').classList.add('hidden');
        document.getElementById('break_time').classList.add('hidden');
        document.getElementById('standard_working_time').classList.add('hidden');
        document.getElementById('over_time').classList.add('hidden');

        // 入力フィールドの値を "00:00" に設定する
        document.getElementById('start_time').value = "00:00";
        document.getElementById('end_time').value = "00:00";
        document.getElementById('break_time').value = "00:00";
        document.getElementById('standard_working_time').value = "00:00";
        document.getElementById('over_time').value = "00:00";
    } else {
        // ラベルと入力フィールドを表示する
        document.getElementById('startTimeLabel').classList.remove('hidden');
        document.getElementById('endTimeLabel').classList.remove('hidden');
        document.getElementById('breakTimeLabel').classList.remove('hidden');
        document.getElementById('standardWorkingTimeLabel').classList.remove('hidden');
        document.getElementById('overTimeLabel').classList.remove('hidden');
        document.getElementById('start_time').classList.remove('hidden');
        document.getElementById('end_time').classList.remove('hidden');
        document.getElementById('break_time').classList.remove('hidden');
        document.getElementById('standard_working_time').classList.remove('hidden');
        document.getElementById('over_time').classList.remove('hidden');

        // 入力フィールドの値を空にする
        document.getElementById('start_time').value = "";
        document.getElementById('end_time').value = "";
        document.getElementById('break_time').value = "";
        document.getElementById('standard_working_time').value = "";
        document.getElementById('over_time').value = "";
    }
}
document.getElementById('attendanceForm').addEventListener('submit', function(event) {
    var selectedMonth = document.getElementById('selectedMonth').value;
    var selectedDay = document.getElementById('daySelect').value;

    if (selectedMonth === '' || selectedDay === '') {
        alert('月と日付を選択してください。');
        event.preventDefault(); // フォームの送信をキャンセルする
    }
});


</script>

</body>
</html>
