<?php
session_start();

// もし既にログインしていれば、トップページにリダイレクト
if (isset($_SESSION['mail'])) {
  header("Location: top.php");
  exit();
}
var_dump($_SESSION);
// データベースへの接続情報を設定します
mb_internal_encoding("utf8");
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "AttendanceManagement";

// データベースへの接続を確立します
$conn = new mysqli($servername, $username, $password, $dbname);

// データベース接続エラーの確認
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// POSTリクエストを受け取った場合
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // フォームからの入力を取得
  $email = $_POST['mail'];
  $password = $_POST['password'];

  // データベースから入力されたメールアドレスと一致するユーザーを取得します
  $sql = "SELECT * FROM users WHERE mail = '$email'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // パスワードが一致するか確認します
    if (password_verify($password, $row['password'])) {
      // password_verify() 関数を使用して、データベースから取得したハッシュ化されたパスワードとユーザーが入力したパスワードを比較しています。
      // ログイン成功
      $_SESSION['mail'] = $email;
      $_SESSION['role'] = ($row['authority'] == 1) ? '管理者' : '一般';
      $_SESSION['user_id'] = $row['id']; // ユーザーIDを保存
      $_SESSION['family_name'] = $row['family_name'];
      $_SESSION['last_name'] = $row['last_name'];
      $_SESSION['family_name_kana'] = $row['family_name_kana'];
      $_SESSION['postal_code'] = $row['postal_code'];
      $_SESSION['address'] = $row['address'];
      $_SESSION['work'] = $row['work'];
      $_SESSION['company_name'] = $row['company_name'];
      $_SESSION['staff_code'] = $row['staff_code'];
      header("Location: top.php");
      exit();
    } else {
      // ログイン失敗
      $error = "メールアドレスまたはパスワードが正しくありません。";
    }
  } else {
    // ユーザーが見つからない場合
    $error = "エラーが発生したためログイン情報を取得できません。";
  }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>ログイン</title>
  <style>
    .error-message {
        color: red;
    }
    div {
      margin:  20px;
    }
    h3 {
      margin:  20px;
    }
    .loginForm{
      display: flex;
      justify-content:center
    }
    main{
      text-align:  center;        /* 中央寄せ */
    }
  </style>
</head>
<body>
  <h3>ログイン</h3>
  <main>
  <div>
    <?php if (isset($error)): ?>
      <p class="error-message"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
      <div class ="loginForm">
        <label for="mail">メールアドレス:</label><br>
        <input type="text" id="mail" name="mail" maxlength="100">
      </div>
      <div class ="loginForm">
        <label for="password">パスワード:</label><br>
        <input type="password" id="password" name="password" maxlength="10">
      </div>
      <button type="submit">ログイン</button>
    </form>
  </div>
  </main>
</body>
</html>
