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
  var_dump($_SESSION);
}

// データベース接続情報
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "AttendanceManagement";

try {
  // データベースへの接続
  $pdo = new PDO("mysql:host={$servername};dbname={$dbname}", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // POSTリクエストから更新する値を取得
  $updateId = $_POST['update_id'];
  $newFamilyName = $_POST['familyName'];
  $newLastName = $_POST['lastName'];
  $newfamilyNameKana = $_POST['familyNameKana'];
  $newLastNameKana = $_POST['lastNameKana'];
  $newMail = $_POST['mail'];
  $newHashedPassword = $_POST['password'];
  $newGender = $_POST['gender'];
  $newPostalCode = $_POST['postalCode'];
  $newAddress = $_POST['address'];
  $newCompanyName = $_POST['companyName'];
  $newBusiness = $_POST['business'];
  $newStaffCode = $_POST['staffCode'];
  $newAuthority = $_POST['authority'];
  // UPDATE文を準備
  $stmt = $pdo->prepare("UPDATE users SET
                          family_name = :familyName,
                          last_name = :lastName,
                           family_name_kana = :familyNameKana,
                          last_name_kana = :lastNameKana,
                          mail = :mail,
                          password = :hashedPassword,
                           gender = :gender,
                           postal_code = :postalCode,
                           address = :address,
                          company_name = :companyName,
                          work = :business,
                          staff_code = :staffCode,
                          authority = :authority
                          WHERE id = :id");

  // バインドパラメータを設定
  $stmt->bindParam(':id', $updateId, PDO::PARAM_INT);
  $stmt->bindParam(':familyName', $newFamilyName, PDO::PARAM_STR);
  $stmt->bindParam(':lastName', $newLastName, PDO::PARAM_STR);
 $stmt->bindParam(':familyNameKana', $newfamilyNameKana, PDO::PARAM_STR);
  $stmt->bindParam(':lastNameKana', $newLastNameKana, PDO::PARAM_STR);
  $stmt->bindParam(':mail', $newMail, PDO::PARAM_STR);
  $stmt->bindParam(':hashedPassword', $newHashedPassword, PDO::PARAM_STR); // ハッシュ化済みのパスワードをバインド
  // UPDATE文を実行
  $stmt->bindParam(':gender', $newGender, PDO::PARAM_STR);
  $stmt->bindParam(':postalCode', $newPostalCode, PDO::PARAM_STR);
  $stmt->bindParam(':address', $newAddress, PDO::PARAM_STR);
  $stmt->bindParam(':companyName', $newCompanyName, PDO::PARAM_STR);
   $stmt->bindParam(':business', $newBusiness, PDO::PARAM_STR);
   $stmt->bindParam(':staffCode', $newStaffCode, PDO::PARAM_STR);
  $stmt->bindParam(':authority', $newAuthority, PDO::PARAM_STR);
  $update = $stmt->execute();

  if ($update) {
    // データベースへの更新が成功した場合
    $success = "familyName と lastName の更新が完了しました。";
    $_SESSION['success'] = $success;
  } else {
    // データベースへの更新が失敗した場合
    $failure = "エラーが発生したため familyName と lastName の更新ができませんでした。";
    $_SESSION['failure'] = $failure;
  }
} catch (PDOException $e) {
  // データベース接続エラーなどが発生した場合
  $error = "データベースへの更新が失敗しました。";
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
  <title>アカウント更新完了画面</title>
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
      <p><a href="http://localhost:8888/AttendanceManagementSystem/userSearch/userSearch.php" id="topBack" >検索画面へ</a></p>
    </div>
  </main>
   <footer>
    <p>Copytifht D.I.Worksl D.I.blog is the one which provides A to Z about programming</p>
  </footer>
</body>
</html>
