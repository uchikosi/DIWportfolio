<?php
session_start();

// データベースへの接続
mb_internal_encoding("utf8");
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "AttendanceManagement";

try {
    $pdo = new PDO("mysql:dbname={$dbname};host={$servername}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("データベースへの接続に失敗しました: " . $e->getMessage());
}

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
}

// ユーザーデータ取得
if (isset($user_id)) {

    // ユーザーデータを取得するクエリを実行
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        die("ユーザーデータが見つかりません");
    }
} else {
    die("ユーザーIDがセットされていません");
}

// セッションの有効期限を設定（1日）
$expireAfter = 60 * 60 * 24; // 1日（秒数で指定）
session_set_cookie_params($expireAfter);
// var_dump($_SESSION);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <link rel="stylesheet" type="text/css" href="../css/registrationInformation.css">
    <title>登録情報</title>
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
      <li class="nav"><a href="logout.php" id="logout">Logout</a></li>
    </ul>
  </header>
  <main>
    <h1 id="title">登録情報</h1>
    <form method="post" action='registrationInformationConfirm.php' id="registrationInformationForm" onsubmit="return validateForm()">
      <div id="leftForm">
      <input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>

      <!-- 名前（姓） -->
      <label for="familyName">名前（姓）:</label>
      <input type="text" id="familyName" name="familyName" maxlength="10" placeholder="漢字orひらがなorカタカナ" value="<?php echo $_SESSION['family_name']; ?>" ><br>

      <!-- カナ（姓） -->
      <label for="familyNameKana">カナ（姓）:</label>
      <input type="text" id="familyNameKana" name="familyNameKana" maxlength="10" placeholder="ひらがなorカタカナ" value="<?php echo $_SESSION['family_name_kana']; ?>" ><br>

      <!-- メールアドレス -->
      <label for="mail">メールアドレス:</label>
      <input type="text" id="mail" name="mail" maxlength="100" placeholder="半角英数字のみ、記号" value="<?php echo $_SESSION['mail']; ?>"><br>

       <!-- 住所 -->
      <label for="address">住所:</label>
      <input type="text" id="address" name="address" maxlength="50" placeholder="全て全角で入力" value="<?php echo $_SESSION['address']; ?>" ><br>

     <!-- 担当業務 -->
      <label for="business">担当業務:</label>
      <input type="text" id="business" name="business" maxlength="50" value="<?php echo $_SESSION['work']; ?>" readonly>※編集できません<br>
      </div>

      <!-- パスワード -->
      <!-- <label for="password">パスワード:</label>
      <input type="password" id="password" name="password" minlength="3" maxlength="10" placeholder="半角英数字 3~10文字" value="<?php //echo $_SESSION['password']; ?>" ><br> -->

      <div id="rightForm">
       <!-- 名前（名） -->
      <label for="lastName">名前（名）:</label>
      <input type="text" id="lastName" name="lastName" maxlength="10" placeholder="漢字orひらがなorカタカナ" value="<?php echo $_SESSION['last_name']; ?>" ><br>

      <!-- カナ（名） -->
      <label for="lastNameKana">カナ（名）:</label>
      <input type="text" id="lastNameKana" name="lastNameKana" maxlength="10" placeholder="ひらがなorカタカナ" value="<?php echo $_SESSION['last_name_kana']; ?>" ><br>

      <!-- 郵便番号 -->
      <label for="postalCode">郵便番号:</label>
      <input type="text" id="postalCode" name="postalCode" maxlength="8" placeholder="半角英数字、-のみ" value="<?php echo $_SESSION['postal_code']; ?>" ><br>

        <!-- 勤務先会社名 -->
      <label for="company_name">勤務先会社名:</label>
      <input type="text" id="company_name" name="company_name" maxlength="50" required value="<?php echo $_SESSION['company_name']; ?>" readonly>※編集できません<br>

      <!-- スタッフコード -->
      <label for="staff_code">スタッフコード:</label>
      <input type="text" id="staff_code" name="staff_code" maxlength="6" required value="<?php echo $_SESSION['staff_code']; ?>" readonly>※編集できません<br>

      <!-- 変更ボタン -->
      <input type="submit" id="change" name="change" value="変更確認へ">
      </div>
    </form>
    <p id="button"><a href="../top.php" id="topBack">TOPへ戻る</a></p>
  </main>
  <footer>Copytifht  is the one which provides A to Z about programming</footer>
  <script type="text/javascript" src="../js/Information.js"></script>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
