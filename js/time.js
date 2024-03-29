function calculateWorkTime() {
  var startTime = document.getElementById('start_time').value;
  var endTime = document.getElementById('end_time').value;
  var breakTime = document.getElementById('break_time').value;

  if (startTime && endTime) {
    var startDate = new Date('2000-01-01T' + startTime);
    var endDate = new Date('2000-01-01T' + endTime);

    // 退勤時間が次の日になる場合は、日付を1日進める
    if (endDate < startDate) {
      endDate.setDate(endDate.getDate() + 1);
    }

    var totalWorkTime = endDate - startDate;

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
    // ラベルと入力フィールドをにする
    document.getElementById('start_time').setAttribute('readonly', 'readonly');
    document.getElementById('end_time').setAttribute('readonly', 'readonly');
    document.getElementById('break_time').setAttribute('readonly', 'readonly');
    document.getElementById('standard_working_time').setAttribute('readonly', 'readonly');
    document.getElementById('over_time').setAttribute('readonly', 'readonly');

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
    document.getElementById('start_time').removeAttribute('readonly');
    document.getElementById('end_time').removeAttribute('readonly');
    document.getElementById('break_time').removeAttribute('readonly');
    document.getElementById('over_time').removeAttribute('readonly');

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

document.getElementById('attendanceForm').addEventListener('submit', function (event) {
  var selectedDate = document.getElementById('categorySelect').value;
  if (selectedDate === '') {
    alert('カテゴリーを選択してください。');
    event.preventDefault(); // フォームの送信をキャンセルする
  }
});

document.getElementById('attendanceForm').addEventListener('submit', function (event) {
  var selectedDate = document.getElementById('start_time').value;
  if (selectedDate === '') {
    alert('出勤時間を選択してください。');
    event.preventDefault(); // フォームの送信をキャンセルする
  }
});

document.getElementById('attendanceForm').addEventListener('submit', function (event) {
  var selectedDate = document.getElementById('end_time').value;
  if (selectedDate === '') {
    alert('退勤時間を選択してください。');
    event.preventDefault(); // フォームの送信をキャンセルする
  }
});
document.getElementById('attendanceForm').addEventListener('submit', function (event) {
  var selectedDate = document.getElementById('break_time').value;
  if (selectedDate === '') {
    alert('休憩時間を選択してください。休憩の無い場合は00:00と入力してください');
    event.preventDefault(); // フォームの送信をキャンセルする
  }
});
