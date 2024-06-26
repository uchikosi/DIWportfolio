<?php
session_start();
// もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: ../login.php");
    exit();
  }

  // ユーザーの権限を取得
  $role = $_SESSION['role'] ?? null;
  $user_id = $_SESSION['user_id'] ?? null;
  $family_name = $_SESSION['family_name'] ?? null;
  $last_name = $_SESSION['last_name'] ?? null;
  // var_dump($user_id);
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="../css/userRegister.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>スタッフデータ入力</title>
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
    <h1 id="title">スタッフ登録フォーム</h1>
      <form method="post" id="registerForm" action="userRegister_confirm.php" onsubmit="return validateForm()">
        <label for="familyName">名前（姓）:</label>
        <input type="text" id="familyName" name="familyName" maxlength="10" autofocus oninput="validateName(this, true)" placeholder="漢字orひらがなorカタカナ"<?php if (isset($_POST['familyName'])) echo 'value="' . htmlspecialchars($_POST['familyName'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="lastName">名前（名）:</label>
        <input type="text" id="lastName" name="lastName" maxlength="10" autofocus oninput="validateName(this, false)" placeholder="漢字orひらがなorカタカナ" <?php if (isset($_POST['lastName'])) echo 'value="' . htmlspecialchars($_POST['lastName'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="familyNameKana">カナ（姓）:</label>
        <input type="text" id="familyNameKana" name="familyNameKana" maxlength="10" oninput="validateNameKana(this, true)" placeholder="ひらがなorカタカナ" <?php if (isset($_POST['familyNameKana'])) echo 'value="' . htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="lastNameKana">カナ（名）:</label>
        <input type="text" id="lastNameKana" name="lastNameKana" maxlength="10" oninput="validateNameKana(this, false)" placeholder="ひらがなorカタカナ" <?php if (isset($_POST['lastNameKana'])) echo 'value="' . htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="mail">メールアドレス:</label>
        <input type="text" id="mail" name="mail" maxlength="100" oninput="validateEmail(this)" placeholder="半角英数字のみ、記号" <?php if (isset($_POST['mail'])) echo 'value="' . htmlspecialchars($_POST['mail'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" minlength="3" maxlength="10" oninput="validatePassword(this)" placeholder="半角英数字 3~10文字">
        <br>

        <label>性別:</label>
        <input type="radio" id="male" name="gender" value="0" <?php if (!isset($_POST['gender']) || (isset($_POST['gender']) && $_POST['gender'] == '0')) echo 'checked'; ?>>
        <label for="male">男</label>
        <input type="radio" id="female" name="gender" value="1" <?php if (isset($_POST['gender']) && $_POST['gender'] == '1') echo 'checked'; ?>>
        <label for="female">女</label>
        <br>

        <label for="postalCode">郵便番号:</label>
        <input type="text" id="postalCode" name="postalCode" maxlength="8" placeholder="半角英数字、-のみ" <?php if (isset($_POST['postalCode'])) echo 'value="' . htmlspecialchars($_POST['postalCode'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="address">住所:</label>
        <input type="text" id="address" name="address" maxlength="50" placeholder="全て全角で入力" oninput="validateAddress(this)" <?php if (isset($_POST['address'])) echo 'value="' . htmlspecialchars($_POST['address'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="company_name">勤務先会社名:</label>
        <input type="text" id="company_name" name="company_name" maxlength="50" placeholder="スペース入力できません" oninput="validateAddress(this)" <?php if (isset($_POST['company_name'])) echo 'value="' . htmlspecialchars($_POST['company_name'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="business">担当業務:</label>
        <input type="text" id="business" name="business" maxlength="50" placeholder="全て全角で入力" oninput="validateAddress(this)" <?php if (isset($_POST['business'])) echo 'value="' . htmlspecialchars($_POST['business'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="staff_code">スタッフコード:</label>
        <input type="text" id="staff_code" name="staff_code" maxlength="6" placeholder="半角数字のみ"oninput="validateAddress(this)" <?php if (isset($_POST['staff_code'])) echo 'value="' . htmlspecialchars($_POST['staff_code'], ENT_QUOTES) . '"'; ?>>
        <br>

        <!-- <label for="image">証明写真:</label>
        <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png, .gif" placeholder="画像選択してください">
        <br> -->

        <!-- <label for="remarks">備考:</label>
        <input type="text" id="remarks" name="remarks" maxlength="1000" placeholder=""oninput="validateAddress(this)" <?php //if (isset($_POST['remarks'])) echo 'value="' . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . '"'; ?>>
        <br> -->

        <label for="authority">アカウント権限:</label>
        <select id="authority" name="authority" required>
            <option value="0" <?php if (isset($_POST['authority']) && $_POST['authority'] == '0') echo 'selected'; ?>>一般</option>
            <option value="1" <?php if (isset($_POST['authority']) && $_POST['authority'] == '1') echo 'selected'; ?>>管理者</option>
        </select>
        <br>

      <button type="submit">確認する</button>
    </form>
    <p id="button"><a href="../top.php" id="topBack">TOPへ戻る</a></p>
  </main>
  <footer>Copytifht the one which provides A to Z about programming</footer>
  <script type="text/javascript" src="../js/common.js"></script>
  <script type="text/javascript" src="../js/userRegister.js"></script>
</body>
</html>
