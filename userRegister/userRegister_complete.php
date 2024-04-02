<?php
// パスワードをハッシュ化
$hashedPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);
// 現在の日時を取得
$registeredTime = date("Y-m-d H:i:s");

// try ブロック:データベースへの登録処理が try内で実行されます。この部分でエラーが発生した場合は、catch ブロックに処理が移ります。
try {
  // PDOを使用してデータベースに接続し、ユーザーの情報をデータベースのusersテーブルに挿入
  $pdo = new PDO("mysql:dbname=AttendanceManagement;host=localhost;", "root", "root");
  // prepare()メソッドは、実行するSQLクエリのプリペアドステートメント（準備された文）を作成します。VALUES以下の各?はプレースホルダであり、後でバインドされる値が入る場所を表しています。
  $stmt = $pdo->prepare("INSERT INTO users (family_name, last_name, family_name_kana, last_name_kana, mail, password, gender, postal_code, address, company_name, work, staff_code, photo, remarks, authority, registered_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
  // execute()メソッドは、プリペアドステートメントを実行します。配列内の値が対応するプレースホルダにバインドされます。実行結果は$resultに格納されます。
  $result = $stmt->execute([
    $_POST['familyName'],
    $_POST['lastName'],
    $_POST['familyNameKana'],
    $_POST['lastNameKana'],
    $_POST['mail'],
    $hashedPassword,
    $_POST['gender'],
    $_POST['postalCode'],
    $_POST['address'],
    $_POST['company_name'],
    $_POST['business'],
    $_POST['staff_code'],
    $_POST['image'],
    $remarks = $_POST['remarks'],
    $_POST['authority'],
    $registeredTime // 現在の日時を使用
  ]);

  if ($result) {
    // データベースへの登録が成功した場合に実行される。$result が true の場合メッセージが出力されます。
    // echo "データベースへの登録が完了しました。";
    $success = "アカウントの登録が完了しました。";
  } else {
    $stmt->errorInfo();
    $failure = "エラーが発生したためアカウント登録できません。";
  }
} catch (PDOException $e) {
  // PDOExceptionが発生した場合にキャッチされる部分です。これは、データベースへの接続やクエリ実行などで発生する可能性のある例外です。エラーメッセージが表示されます。
  echo "データベースへの登録が失敗しました。";
}

session_start();
  // もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: login.php");
    exit();
  }

  // ユーザーの権限を取得
  $role = $_SESSION['role'] ?? null;
  $user_id = $_SESSION['user_id'] ?? null; // ユーザーIDを取得
  $family_name = $_SESSION['family_name'] ?? null;
  $last_name = $_SESSION['last_name'] ?? null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/complete.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>スタッフデータ入力完了</title>
  <style>
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
    <h1 id="title">スタッフデータ入力完了</h1>
    <div id="message">
        <?php
          if (isset($success)) {
            echo $success;// 成功メッセージ
            echo "<br>";
          }

          if (isset($failure)) {
            echo $failure;// 失敗メッセージ
            echo "<br>";
          }
        ?>
      </h1>
    </div>

    <div id="back">
      <p>
        <a href="http://localhost:8888/AttendanceManagementSystem/top.php" id="topBack" >TOPページへ戻る</a>
      </p>
      <p>
        <a href="http://localhost:8888/AttendanceManagementSystem/userSearch/userSearch.php">従業員検索へ</a>
      </p>
    </div>
  </main>
  <footer>
    Copytifht the one which provides A to Z about programming
  </footer>
    <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
