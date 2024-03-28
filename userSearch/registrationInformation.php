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
// var_dump($_SESSION);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../css/common.css">
    <title>TOP</title>
</head>
<body>
<header>
    <h1>勤怠報告</h1>
    <div id="head">
        <p>ようこそ <?php echo $_SESSION['family_name'] . $_SESSION['last_name']; ?>様</p>
        <p> <?php echo $_SESSION['mail']; ?></p>
        <?php if ($role === '管理者'): ?>
            <p>このアカウント権限は管理者です</p>
        <?php endif; ?>
        <p><a href="logout.php">Logout</a></p>
    </div>
</header>
<main>
    <h1>登録情報</h1>
    <form method="post" action=' registrationInformationConfirm.php'>
        <input type='hidden' name='user_id' value='<?php echo $user_id; ?>'>

        <!-- 名前（姓） -->
        <label for="familyName">名前（姓）:</label>
        <input type="text" id="familyName" name="familyName" maxlength="10" placeholder="漢字orひらがな" value="<?php echo $_SESSION['family_name']; ?>" ><br>

        <!-- 名前（名） -->
        <label for="lastName">名前（名）:</label>
        <input type="text" id="lastName" name="lastName" maxlength="10" placeholder="漢字orひらがな" value="<?php echo $_SESSION['last_name']; ?>" ><br>

        <!-- カナ（姓） -->
        <label for="familyNameKana">カナ（姓）:</label>
        <input type="text" id="familyNameKana" name="familyNameKana" maxlength="10" placeholder="カタカナ" value="<?php echo $_SESSION['family_name_kana']; ?>" ><br>

        <!-- カナ（名） -->
        <label for="lastNameKana">カナ（名）:</label>
        <input type="text" id="lastNameKana" name="lastNameKana" maxlength="10" placeholder="カタカナ" value="<?php echo $_SESSION['last_name_kana']; ?>" ><br>

        <!-- メールアドレス -->
        <label for="mail">メールアドレス:</label>
        <input type="text" id="mail" name="mail" maxlength="50" placeholder="@,ドット,半角英数字のみ" value="<?php echo $_SESSION['mail']; ?>" readonly><br>

        <!-- パスワード -->
        <!-- <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" minlength="3" maxlength="10" placeholder="半角英数字 3~10文字" value="<?php //echo $_SESSION['password']; ?>" ><br> -->

        <!-- 郵便番号 -->
        <label for="postalCode">郵便番号:</label>
        <input type="text" id="postalCode" name="postalCode" maxlength="8" required placeholder="半角英数字" value="<?php echo $_SESSION['postal_code']; ?>" ><br>

        <!-- 住所 -->
        <label for="address">住所:</label>
        <input type="text" id="address" name="address" maxlength="100" required placeholder="日本語で入力" value="<?php echo $_SESSION['address']; ?>" ><br>

        <!-- 勤務先会社名 -->
        <label for="company_name">勤務先会社名:</label>
        <input type="text" id="company_name" name="company_name" maxlength="50" required value="<?php echo $_SESSION['company_name']; ?>" readonly>※編集できません<br>

        <!-- 担当業務 -->
        <label for="business">担当業務:</label>
        <input type="text" id="business" name="business" maxlength="50" value="<?php echo $_SESSION['work']; ?>" readonly>※編集できません<br>

        <!-- スタッフコード -->
        <label for="staff_code">スタッフコード:</label>
        <input type="text" id="staff_code" name="staff_code" maxlength="6" required value="<?php echo $_SESSION['staff_code']; ?>" readonly>※編集できません<br>

        <!-- 変更ボタン -->
        <input type="submit" id="change" name="change" value="変更確認へ">
    </form>
</main>
<footer>
    Copytifht is the one which provides A to Z about programming
</footer>
</body>
</html>
