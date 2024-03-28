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
  var_dump($_SESSION);
}

// POSTリクエストの場合
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // フォームからのデータを取得
  $user_id = $_POST['user_id'];
  $newFamilyName = $_POST['familyName'];
  $newlastName = $_POST['lastName'];
  $newfamilyNameKana = $_POST['familyNameKana'];
  $newLastNameKana = $_POST['lastNameKana'];
  $newMail = $_POST['mail'];
  $newPassword = $_POST['password'];
  $newPostalCode = $_POST['postalCode'];
  $newPrefecture = $_POST['prefecture'];
  $newAddress = $_POST['address'];
  // var_dump($newMail);
}

session_start();
  // もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: login.php");
    exit();
  }

  // ユーザーの権限を取得
  $role = $_SESSION['role'] ?? null;

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>更新確認</title>
  <style>
    main {
      margin: 10px;
    }
    table {
      width: 200px;               /* 幅指定 */
      height: 90px;               /* 高さ指定 */
      margin:  0 auto;            /* 中央寄せ */
    }
    .button-container {
      padding: 10px;              /* 余白指定 */
      height: 50px;              /* 高さ指定 */
      text-align:  center;        /* 中央寄せ */
      display: flex;
      justify-content:center
    }
  </style>
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
    <h2>更新確認画面</h2>
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
          <td>郵便番号</td>
          <td><?php echo $newPostalCode; ?></td>
        </tr>
        <tr>
          <td>住所</td>
          <td><?php echo $newAddress ; ?></td>
        </tr>
      </table>
    </div>

    <div class="button-container">
      <form method="post" action="registrationInformation.php?id=<?php echo $user_id; ?>">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <input type="hidden" name="familyName" value="<?php echo htmlspecialchars($newFamilyName, ENT_QUOTES); ?>">
        <input type="hidden" name="lastName" value="<?php echo htmlspecialchars($newlastName, ENT_QUOTES); ?>">
        <input type="hidden" name="familyNameKana" value="<?php echo htmlspecialchars($newfamilyNameKana, ENT_QUOTES); ?>">
        <input type="hidden" name="lastNameKana" value="<?php echo htmlspecialchars($newLastNameKana, ENT_QUOTES); ?>">
        <input type="hidden" name="mail" value="<?php echo htmlspecialchars($newMail, ENT_QUOTES); ?>">
        <input type="hidden" name="password" value="<?php echo htmlspecialchars($newPassword, ENT_QUOTES); ?>">
        <input type="hidden" name="postalCode" value="<?php echo htmlspecialchars($newPostalCode, ENT_QUOTES); ?>">
        <input type="hidden" name="address" value="<?php echo htmlspecialchars($newAddress, ENT_QUOTES); ?>">
        <input type="submit" value="前に戻る">
      </form>
      <!-- htmlspecialchars は、HTMLエスケープ処理 PHP関数　これを使うと、HTML タグや特殊文字をエスケープする。 -->

      <!-- 更新処理をしてアカウント更新完了画面に遷移 -->
      <form method="post" action="registrationInformationComplete.php">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <input type="hidden" name="familyName" value="<?php echo isset($_POST['familyName']) ? htmlspecialchars($_POST['familyName'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="lastName" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="familyNameKana" value="<?php echo isset($_POST['familyNameKana']) ? htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="lastNameKana" value="<?php echo isset($_POST['lastNameKana']) ? htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="mail" value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="postalCode" value="<?php echo isset($_POST['postalCode']) ? htmlspecialchars($_POST['postalCode'], ENT_QUOTES) : ''; ?>">
        <input type="hidden" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : ''; ?>">

        <input type="submit" name="" value="更新">
      </form>
    </div>
  </main>
  <footer>
    <p>Copytifht D.I.Worksl D.I.blog is the one which provides A to Z about programming</p>
  </footer>

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
</body>
</html>
