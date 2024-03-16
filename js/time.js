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
document.getElementById('attendanceForm').addEventListener('submit', function (event) {
  var selectedDate = document.getElementById('selectedDate').value;
  if (selectedDate === '') {
    alert('年月日を選択してください。');
    event.preventDefault(); // フォームの送信をキャンセルする
  }
});
