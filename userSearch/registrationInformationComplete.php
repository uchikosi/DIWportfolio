<?php
// セッションの開始
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

// データベースへの接続
mb_internal_encoding("utf8");
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "AttendanceManagement";

try {
    $pdo = new PDO("mysql:dbname={$dbname};host={$servername}", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // POSTメソッドでリクエストが送信された場合の処理
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // ユーザーIDを取得
        $user_id = $_POST['user_id'] ?? null;

        // ユーザーIDがセットされているか確認
        if ($user_id) {
            // フォームから送られてきたデータを取得
            $familyName = $_POST['familyName'] ?? '';
            $lastName = $_POST['lastName'] ?? '';
            $familyNameKana = $_POST['familyNameKana'] ?? '';
            $lastNameKana = $_POST['lastNameKana'] ?? '';
            $mail = $_POST['mail'] ?? '';
            // $password = $_POST['password'] ?? '';
            $postalCode = $_POST['postalCode'] ?? '';
            $address = $_POST['address'] ?? '';

            // データベース更新処理を実行
            $stmt = $pdo->prepare("UPDATE users SET
                family_name = :familyName,
                last_name = :lastName,
                family_name_kana = :familyNameKana,
                last_name_kana = :lastNameKana,
                mail = :mail,
                postal_code = :postalCode,
                address = :address
                WHERE id = :user_id");

            $stmt->bindParam(':familyName', $familyName, PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':familyNameKana', $familyNameKana, PDO::PARAM_STR);
            $stmt->bindParam(':lastNameKana', $lastNameKana, PDO::PARAM_STR);
            $stmt->bindParam(':mail', $mail, PDO::PARAM_STR);
            $stmt->bindParam(':postalCode', $postalCode, PDO::PARAM_STR);
            $stmt->bindParam(':address', $address, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

            // クエリを実行
            $update = $stmt->execute();

            // 更新が成功
    $success = "更新が完了しました。";
    $_SESSION['success'] = $success;
    // セッションの値も更新する
    $_SESSION['family_name'] = $familyName;
    $_SESSION['last_name'] = $lastName;
    $_SESSION['family_name_kana'] = $familyNameKana;
    $_SESSION['last_name_kana'] = $lastNameKana;
    $_SESSION['mail'] = $mail;
    $_SESSION['postal_code'] = $postalCode;
    $_SESSION['address'] = $address;
        }
    } else {
        // POSTメソッドでない場合の処理
        echo "エラーが発生したため更新ができませんでした。";
    }
} catch (PDOException $e) {
    die("データベースへの接続に失敗しました: " . $e->getMessage());
}

// 更新成功または失敗のメッセージを受け取る
$successMessage = isset($_SESSION['success']) ? $_SESSION['success'] : null;
$failureMessage = isset($_SESSION['failure']) ? $_SESSION['failure'] : null;

// セッション変数の削除（メッセージは不要になったので）
unset($_SESSION['success']);
unset($_SESSION['failure']);
?>


<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>更新完了画面</title>
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
    <div>
      <h1 id="databaseRegistrationResults">
        <?php
          // 成功メッセージの表示
          if ($successMessage) {
            echo $successMessage;
            echo "<br>";
          }

          // 失敗メッセージの表示
          if ($failureMessage) {
            echo $failureMessage;
            echo "<br>";
          }
        ?>
      </h1>
    </div>

    <div>
      <p>
        <a href="http://localhost:8888/AttendanceManagementSystem/top.php" id="topBack" >TOPページへ戻る</a>
      </p>
      <p><a href="http://localhost:8888/AttendanceManagementSystem/userSearch/registrationInformation.php" id="" >登録情報画面へ</a></p>
    </div>
  </main>
  <footer>Copytifht is the one which provides A to Z about programming</footer>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
