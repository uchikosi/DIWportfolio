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

// GETリクエストからidを取得する
if(isset($_GET['id'])) {
    $delete_id = $_GET['id'];

    // データベースからユーザー情報を取得するクエリを実行する
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$delete_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // 削除ボタンが押された場合
    if(isset($_POST['delete'])) {
        // ユーザーを削除するクエリを実行する
        $delete_query = "DELETE FROM users WHERE id = ?";
        $stmt = $pdo->prepare($delete_query);
        $stmt->execute([$delete_id]);

        // 削除が成功した場合
        if($stmt) {
            echo "<script>alert('ユーザーが削除されました。');</script>";
            echo "<script>window.location.href = 'http://localhost:8888/AttendanceManagementSystem/userSearch/userSearch.php';</script>";
        } else {
            echo "<script>alert('削除に失敗しました。');</script>";
        }
    }
    // ユーザーが存在しない場合や削除された場合、リダイレクトして不正なアクセスを防ぐ
    // ブラウザの戻るボタンを使用しても削除されたユーザーのページに戻ることはできません。
    if(!$user) {
        header("Location: userSearch.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>削除確認画面</title>
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
    <h1>ユーザー削除確認</h1>
    <?php if(isset($user)) : ?>
    <ul>
      <!-- <li>ID: <?php //echo $user['id']; ?></li> -->
      <li>名前（姓）: <?php echo $user['family_name']; ?></li>
      <li>名前（名）: <?php echo $user['last_name']; ?></li>
      <li>カナ（姓）: <?php echo $user['family_name_kana']; ?></li>
      <li>カナ（名）: <?php echo $user['last_name_kana']; ?></li>
      <li>メールアドレス: <?php echo $user['mail']; ?></li>
      <li>パスワード: <?php echo 'セキュリティーの問題で表示されません'; ?></li>
      <li>性別: <?php echo $user['gender'] == 0 ? '男性' : '女性'; ?></li>
      <li>郵便番号: <?php echo $user['postal_code']; ?></li>
      <li>住所: <?php echo $user['address']; ?></li>
      <li>勤務先名: <?php echo $user['company_name']; ?></li>
      <li>担当業務: <?php echo $user['work']; ?></li>
      <li>スタッフコード: <?php echo $user['staff_code']; ?></li>
      <li>アカウント権限: <?php echo $user['authority'] == 0 ? '一般' : '管理者'; ?></li>
      <li>登録日時: <?php echo date('Y年n月j日', strtotime($user['registered_time'])); ?></li>
      <li>更新日時: <?php echo date('Y年n月j日', strtotime($user['update_time'])); ?></li>
    </ul>
    <form id="deleteForm" method="post">
      <button><a href="http://localhost:8888/AttendanceManagementSystem/userSearch/userSearch.php">戻る</a></button>
      <input type="submit" name="delete" value="削除">
    </form>
    <?php else : ?>
      <p>ユーザー情報が見つかりません。</p>
    <?php endif; ?>
  </main>
  <footer>Copytifht  is the one which provides A to Z about programming</footer>

  <script>
    document.getElementById("deleteForm").addEventListener("submit", function(event) {
      var confirmMessage = "一度削除したアカウント情報は元に戻させません。\n";
      confirmMessage += "本当に削除してよろしいですか？\n";
      confirmMessage += "名前: <?php echo $user['family_name'], $user['last_name']; ?>\n";
      confirmMessage += "スタッフコード:<?php echo $user['staff_code']; ?>\n";

      // 確認ダイアログを表示し、キャンセルの場合はフォームの送信をキャンセルする
      if(!confirm(confirmMessage)) {
        event.preventDefault();
      }
    });
  </script>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
