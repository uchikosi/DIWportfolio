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
  // var_dump($_SESSION);
}

// POSTリクエストの場合
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // フォームからのデータを取得
  $updateId = $_POST['update_id'];
  $newFamilyName = $_POST['familyName'];
  $newlastName = $_POST['lastName'];
  $newfamilyNameKana = $_POST['familyNameKana'];
  $newLastNameKana = $_POST['lastNameKana'];
  $newMail = $_POST['mail'];
  $newPassword = $_POST['password'];
  $newGender = $_POST['gender'];
  $newPostalCode = $_POST['postalCode'];
  $newPrefecture = $_POST['prefecture'];
  $newAddress = $_POST['address'];
  $newCompanyName = $_POST['companyName'];
  $newBusiness = $_POST['business'];
  $newStaffCode = $_POST['staffCode'];
  $newAuthority = $_POST['authority'];
  // var_dump($newMail);
}

// パスワードの文字数チェック
if (isset($_POST['password']) && strlen($_POST['password']) > 10) {
  $_SESSION['error'] = "パスワードの文字数は10文字以内にしてください。";
  header("Location: update.php"); // update.php にリダイレクト
  exit(); // 遷移をブロックするためにスクリプトを終了
}

// 入力された値をセッションに保存する
$_SESSION['form_values'] = $_POST;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/confirm.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>従業員情報更新確認</title>
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
    <h1 id="title">従業員情報編集確認</h1>
    <div id="">
      <table>
        <tr>
          <td>名前（姓）</td>
          <td><?php echo  $newFamilyName; ?></td>
        </tr>
        <tr>
          <td>名前（名）</td>
          <td><?php echo $newlastName; ?></td>
        </tr>
        <tr>
          <td>カナ（姓）</td>
          <td><?php echo $newfamilyNameKana ; ?></td>
        </tr>
        <tr>
          <td>カナ（名）</td>
          <td><?php echo $newLastNameKana; ?></td>
        </tr>
        <tr>
          <td>メールアドレス</td>
          <td class = "longText"><?php echo $newMail; ?></td>
        </tr>
        <tr>
          <td>パスワード</td>
          <td><?php echo str_repeat("●", strlen($newPassword)); ?></td>
        </tr>
        <tr>
          <td>性別</td>
          <td><?php echo ($newGender['gender'] == 0 ? '男性' : '女性'); ?></td>
        </tr>
        <tr>
          <td>郵便番号</td>
          <td><?php echo $newPostalCode; ?></td>
        </tr>
        <tr>
          <td>住所</td>
          <td><?php echo $newAddress ; ?></td>
        </tr>
        <tr>
          <td>勤務先会社名</td>
          <td><?php echo $newCompanyName; ?></td>
        </tr>
        <tr>
          <td>担当業務</td>
          <td><?php echo $newBusiness; ?></td>
        </tr>
        <tr>
          <td>スタッフコード</td>
          <td><?php echo $newStaffCode; ?></td>
        </tr>
        <tr>
          <td>アカウント権限</td>
          <td><?php echo ($newAuthority['authority'] == 0 ? '一般' : '管理者'); ?></td>
        </tr>
      </table>
    </div>

    <div class="button-container">
      <form method="post" action="userUpdate.php?id=<?php echo $updateId; ?>">
        <input type="hidden" name="update_id" value="<?php echo $updateId; ?>">
        <input type="hidden" name="familyName" value="<?php echo htmlspecialchars($newFamilyName, ENT_QUOTES); ?>">
        <input type="hidden" name="lastName" value="<?php echo htmlspecialchars($newlastName, ENT_QUOTES); ?>">
        <input type="hidden" name="familyNameKana" value="<?php echo htmlspecialchars($newfamilyNameKana, ENT_QUOTES); ?>">
        <input type="hidden" name="lastNameKana" value="<?php echo htmlspecialchars($newLastNameKana, ENT_QUOTES); ?>">
        <input type="hidden" name="mail" value="<?php echo htmlspecialchars($newMail, ENT_QUOTES); ?>">
        <input type="hidden" name="password" value="<?php echo htmlspecialchars($newPassword, ENT_QUOTES); ?>">
        <input type="hidden" name="postalCode" value="<?php echo htmlspecialchars($newPostalCode, ENT_QUOTES); ?>">
        <input type="hidden" name="address" value="<?php echo htmlspecialchars($newAddress, ENT_QUOTES); ?>">
        <input type="hidden" name="gender" value="<?php echo htmlspecialchars($newGender, ENT_QUOTES); ?>">
        <input type="hidden" name="companyName" value="<?php echo htmlspecialchars($newCompanyName, ENT_QUOTES); ?>">
        <input type="hidden" name="business" value="<?php echo htmlspecialchars($newBusiness, ENT_QUOTES); ?>">
        <input type="hidden" name="staffCode" value="<?php echo htmlspecialchars($newStaffCode, ENT_QUOTES); ?>">
        <input type="hidden" name="authority" value="<?php echo htmlspecialchars($newAuthority, ENT_QUOTES); ?>">
        <input type="submit" value="前に戻る">
      </form>
      <!-- htmlspecialchars は、HTMLエスケープ処理 PHP関数　これを使うと、HTML タグや特殊文字をエスケープする。 -->

      <!-- 更新処理をしてアカウント更新完了画面に遷移 -->
      <form method="post" action="userUpdateComplete.php">
        <input type="hidden" name="update_id" value="<?php echo $updateId; ?>">

        <input type="hidden" name="familyName" value="<?php echo isset($_POST['familyName']) ? htmlspecialchars($_POST['familyName'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="lastName" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="familyNameKana" value="<?php echo isset($_POST['familyNameKana']) ? htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="lastNameKana" value="<?php echo isset($_POST['lastNameKana']) ? htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="mail" value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="gender" value="<?php echo isset($_POST['gender']) ? htmlspecialchars($_POST['gender'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="postalCode" value="<?php echo isset($_POST['postalCode']) ? htmlspecialchars($_POST['postalCode'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="companyName" value="<?php echo isset($_POST['companyName']) ? htmlspecialchars($_POST['companyName'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="business" value="<?php echo isset($_POST['business']) ? htmlspecialchars($_POST['business'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="staffCode" value="<?php echo isset($_POST['staffCode']) ? htmlspecialchars($_POST['staffCode'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="authority" value="<?php echo isset($_POST['authority']) ? htmlspecialchars($_POST['authority'], ENT_QUOTES) : ''; ?>">

        <input type="submit" name="" value="更新">
      </form>
    </div>
  </main>
  <footer>Copytifht is the one which provides A to Z about programming</footer>

  <script>
    var longTextElements = document.getElementsByClassName('longText');
    for (var i = 0; i < longTextElements.length; i++) {
      var element = longTextElements[i];
      var text = element.innerText;
      if (text.length > 50) {
        element.innerHTML = text.replace(/(.{50})/g, '$1<br>');
      }
    }
  </script>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
