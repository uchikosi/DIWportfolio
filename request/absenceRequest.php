<?php
session_start();
// セッションの有効期限を設定（1日）
$expireAfter = 60 * 60 * 24; // 1日（秒数で指定）
session_set_cookie_params($expireAfter);

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
  $family_name_kana = $_SESSION['family_name_kana'] ?? null;
  $last_name_kana = $_SESSION['last_name_kana'] ?? null;
  $staff_code = $_SESSION['staff_code'] ?? null;
  var_dump($_SESSION);
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/allRequest.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>欠勤、遅刻、早退連絡</title>
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
    <h1 id="title">欠勤、遅刻、早退連絡</h1>
    <form action="absenceRequestConfirm.php" method="POST" id="updateForm">
      <input type="hidden" name="id" value="">

      <label for="name">名前:</label>
      <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($family_name . $last_name, ENT_QUOTES); ?>"readonly>
      <i>※編集できません</i>
      <br>

      <label for="name_kana">名前(カナ):</label>
      <input type="text" id="name_kana" name="name_kana" value="<?php echo htmlspecialchars($family_name_kana . $last_name_kana, ENT_QUOTES); ?>"readonly>
      <i>※編集できません</i>
      <br>

      <label for="staff_code">スタッフコード:</label>
      <input type="text" id="staff_code" name="staff_code" value="<?php echo htmlspecialchars($staff_code, ENT_QUOTES); ?>"readonly>
      <i>※編集できません</i>
      <br>

      <label for="request_date_start">申請年月日:</label>
      <input type="date" name="request_date_start" id="request_date_start" placeholder="" oninput="validateAddress(this)" <?php if (!empty($_POST['request_date_start'])) echo 'value="' . htmlspecialchars($_POST['request_date_start'], ENT_QUOTES) . '"'; ?>>

      <label for="request_date_end">〜</label>
      <input type="date" name="request_date_end" id="request_date_end" placeholder="" oninput="validateAddress(this)" <?php if (!empty($_POST['request_date_end'])) echo 'value="' . htmlspecialchars($_POST['request_date_end'], ENT_QUOTES) . '"'; ?>>
      <br>

      <label for="category">区分:</label>
      <select id="category" name="category">
        <option value="" <?php if(!isset($_POST['category']) || $_POST['category'] === '遅刻') echo "selected disabled"; ?>>選択してください</option>
        <option value="欠勤" <?php if (isset($_POST['category']) && $_POST['category'] === '欠勤') echo 'selected'; ?>>欠勤</option>
        <option value="早退" <?php if (isset($_POST['category']) && $_POST['category'] === '早退') echo 'selected'; ?>>早退</option>
        <option value="遅刻" <?php if (isset($_POST['category']) && $_POST['category'] === '遅刻') echo 'selected'; ?>>遅刻</option>
      </select>

      <label for="remarks">備考:</label>
      <input type="text" id="remarks" name="remarks" maxlength="100" placeholder="欠勤の理由等を記入してください" placeholder="日本語で入力"oninput="validateAddress(this)" <?php if (isset($_POST['remarks'])) echo 'value="' . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . '"'; ?>>
      <br>

      <input type="submit" value="入力確認画面へ" >
    </form>
    <p>申請後に申請した日の勤務入力を行ってください</p>
    <p id="button"><a href="../top.php" id="topBack">TOPへ戻る</a></p>
  </main>
  <footer>Copytifht is the one which provides A to Z about programming</footer>
  <script>
    // request_date_endの日付がrequest_date_startより前の日付を選択できないように制約を設ける
    document.getElementById("request_date_end").addEventListener("change", function() {
      var requestDate1 = document.getElementById("request_date_start").value;
      var requestDate2 = document.getElementById("request_date_end").value;
      // request_date_startまたはrequest_date_endのどちらかが空の場合は制約をかけない
      if (requestDate1 !== "" && requestDate2 !== "") {
        if (requestDate2 < requestDate1) {
          alert("申請終了日は申請開始日より後の日付を選択してください。");
          document.getElementById("request_date_end").value = "";
        }
      }
    });

      // request_date_startの日付がrequest_date_endより後の日付を選択できないように制約を設ける
    document.getElementById("request_date_start").addEventListener("change", function() {
      var requestDate1 = document.getElementById("request_date_start").value;
      var requestDate2 = document.getElementById("request_date_end").value;

      // request_date_startまたはrequest_date_endのどちらかが空の場合は制約をかけない
      if (requestDate1 !== "" && requestDate2 !== "") {
        if (requestDate1 > requestDate2) {
          alert("申請開始日は申請終了日より前の日付を選択してください。");
          document.getElementById("request_date_start").value = "";
        }
      }
    });

    document.getElementById("updateForm").addEventListener("submit", function(event) {
    var requestDateStart = document.getElementById("request_date_start").value;
    var requestDateEnd = document.getElementById("request_date_end").value;
    var category = document.getElementById("category").value;

    // 申請年月日の開始日が選択されていない場合、エラーメッセージを表示してフォーム送信をキャンセル
    if (requestDateStart === "") {
      event.preventDefault(); // フォーム送信をキャンセル
      alert("申請年月日の開始日を選択してください。");
      return;
    }

    // 申請年月日の終了日が選択されていない場合、エラーメッセージを表示してフォーム送信をキャンセル
    if (requestDateEnd === "") {
      event.preventDefault(); // フォーム送信をキャンセル
      alert("申請年月日の終了日を選択してください。");
      return;
    }

    // 区分が選択されていない場合、エラーメッセージを表示してフォーム送信をキャンセル
    if (category === "") {
      event.preventDefault(); // フォーム送信をキャンセル
      alert("区分を選択してください。");
      return;
    }
  });
  </script>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
