
<?php
session_start();

// データベース接続
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'AttendanceManagement';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
// if ($conn->connect_error) {
//   die("Connection failed: " . $conn->connect_error);
// }
$update_id = $_GET['id'];
$_SESSION['update_id'] = $_GET['id'];

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
  // var_dump($_SESSION);
}

// ユーザーデータ取得
if (isset($_SESSION['update_id'])) {
  $updateId = $_SESSION['update_id'];

  // ユーザーデータを取得するクエリを実行
  $query = "SELECT * FROM users WHERE id = $updateId";
  $result = $conn->query($query);

  if ($result && $result->num_rows > 0) {
    $userData = $result->fetch_assoc();
  } else {
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
  <link rel="stylesheet" type="text/css" href="../css/userUpdate.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
<title>従業員情報編集</title>
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
    <h1 id="title">従業員情報編集</h1>
    <form method='post' id="updateForm" action='userUpdateConfirm.php' onsubmit="return validateForm()">
      <div id="lfteForm">
        <input type='hidden' name='update_id' value='<?php echo $update_id; ?>'>

        <label for='family_name'>名前(姓)</label>
        <input type='text' name='familyName' id="familyName" maxlength="10" autofocus placeholder="漢字orひらがなorカタカナ" oninput="validateName(this, true)" value='<?php
          if (isset($_POST['familyName'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['familyName'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['family_name'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

         <label for='family_name_kana'>カナ(姓)</label>
        <input type='text' id="familyNameKana" name="familyNameKana" maxlength="10" oninput="validateNameKana(this, true)" placeholder="ひらがなorカタカナ" value='<?php
        if (isset($_POST['familyNameKana'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['family_name_kana'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='mail'>メールアドレス</label>
        <input type='text' id="mail" name="mail" maxlength="100" oninput="validateEmail(this)" placeholder="半角英数字のみ、記号"" value='<?php if (isset($_POST['mail'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['mail'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['mail'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label>性別</label>
        <label><input type='radio' id="male" name='gender' value='0' <?php echo ((isset($_POST['gender']) && $_POST['gender'] == 0) || (!isset($_POST['gender']) && $userData['gender'] == 0) ? 'checked' : ''); ?>> 男性</label>
        <label><input type='radio' id="female" name='gender' value='1' <?php echo ((isset($_POST['gender']) && $_POST['gender'] == 1) || (!isset($_POST['gender']) && $userData['gender'] == 1) ? 'checked' : ''); ?>> 女性</label>
        <br>

        <label for='address'>住所:</label>
        <input type='text' id="address" name="address" maxlength="50" placeholder="全て全角で入力" oninput="validateAddress(this)" value='<?php
          if (isset($_POST['address'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['address'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['address'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>



        <label for='business'>担当業務:</label>
        <input type='text' id="business" name="business" maxlength="50" placeholder="全て全角で入力" value='<?php
          if (isset($_POST['business'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['business'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['work'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='authority'>アカウント権限</label>
        <select id="authority" name="authority" required>
          <option value='0' <?php echo (isset($_POST['authority']) && $_POST['authority'] == 0 ? 'selected' : ($userData['authority'] == 0 ? 'selected' : '')); ?>>一般</option>
          <option value='1' <?php echo (isset($_POST['authority']) && $_POST['authority'] == 1 ? 'selected' : ($userData['authority'] == 1 ? 'selected' : '')); ?>>管理者</option>
        </select>
        <br>
      </div>

      <div  id="rightForm">
        <label for='last_name'>名前(名)</label>
        <input type='text' name='lastName' id="lastName" maxlength="10" autofocus placeholder="漢字orひらがなorカタカナ" oninput="validateName(this, true)" value='<?php
          if (isset($_POST['lastName'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['lastName'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['last_name'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='last_name_kana'>カナ(名)</label>
        <input type='text' id="lastNameKana" name="lastNameKana" maxlength="10" oninput="validateNameKana(this, false)" placeholder="ひらがなorカタカナ" value='<?php if (isset($_POST['lastNameKana'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['last_name_kana'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='password'>パスワード:</label>
        <input type='password' id="password" name="password" oninput="validatePassword(this)" placeholder="半角英数字 3~10文字" value='<?php
          if (isset($_POST['password'])){
              // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['password'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData[''], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='postal_code'>郵便番号:</label>
        <input type='text' id="postalCode" name="postalCode" maxlength="8" placeholder="半角英数字、-のみ" value='<?php
          if (isset($_POST['postalCode'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['postalCode'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
          // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['postal_code'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='company_name'>勤務先会社名:</label>
        <input type='text' id="companyName" name="companyName" maxlength="50" placeholder="スペース入力できません" value='<?php
          if (isset($_POST['companyName'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['companyName'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['company_name'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <label for='staff_code'>スタッフコード:</label>
        <input type='text' id="staffCode" name="staffCode" maxlength="6" placeholder="半角数字のみ" value='<?php
          if (isset($_POST['staffCode'])){
            // userUpdateConfirm.phpから遷移した場合
            echo htmlspecialchars($_POST['staffCode'], ENT_QUOTES);
          } elseif (isset($_GET['id'])) {
            // userSearch.phpから遷移した場合
            echo htmlspecialchars($userData['staff_code'], ENT_QUOTES);
          } else {
            // その他の場合
            echo '';
          }?>'>
        <br>

        <input type='submit' value='確認する'>
      </div>

      <?php
        // エラーメッセージの表示
        // if (isset($_SESSION['error'])) {
        //   echo "<div style='color: red; font-size: 18px;'>" . $_SESSION['error'] . "</div>";
        //   unset($_SESSION['error']); // エラーメッセージを表示した後はセッションから削除する
        // }
      ?>
    </form>
    <p id="button"><a href="../top.php" id="topBack">TOPへ戻る</a></p>
  </main>
  <footer>Copytifht is the one which provides A to Z about programming</footer>
  <script type="text/javascript" src="../js/userUpdate.js"></script>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
