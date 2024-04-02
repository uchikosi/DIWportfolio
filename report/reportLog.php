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
    $staff_code = $_SESSION['staff_code'] ?? null;
    var_dump($_SESSION);
  }

  // データベースに接続
  $pdo = new PDO("mysql:dbname=AttendanceManagement;host=localhost;", "root", "root");

  // フォームからの検索条件を取得
  $category = isset($_GET['category']) ? $_GET['category'] : '';
  $registration_date = isset($_GET['registration_date']) ? $_GET['registration_date'] : '';

// SQLクエリの作成
$sql = "SELECT *, DATEDIFF(request_date_end, request_date_start) + 1 AS duration FROM holidayRequest WHERE staff_code = ?";

$params = [$staff_code]; // ログインユーザーのスタッフコードを設定

if (!empty($category)) {
  $sql .= " AND category = ?";
  $params[] = $category;
}
if (!empty($registration_date)) {
  $sql .= " AND DATE(registered_time) = ?";
  $params[] = $registration_date;
}

// SQLクエリを実行
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="../css/reportLog.css">
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>申請、連絡ログ</title>
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
    <h1 id="title">連絡、申請一覧</h1>
    <!-- 検索フォーム -->
    <form method="get" action="reportLog.php" id="reportLog">
      <label for="registration_date">申請年月日:</label>
      <input type="date" id="registration_date" name="registration_date" value="<?php echo htmlspecialchars($registration_date, ENT_QUOTES); ?>">
      <br>

      <label for="category">区分:</label>
      <select id="category" name="category">
        <option value="" selected disabled>選択してください</option>
        <option value="有休"<?php if ($category === '有休') echo ' selected'; ?>>有休</option>
        <option value="代休"<?php if ($category === '代休') echo ' selected'; ?>>代休</option>
        <option value="欠勤"<?php if ($category === '欠勤') echo ' selected'; ?>>欠勤</option>
        <option value="遅刻"<?php if ($category === '遅刻') echo ' selected'; ?>>遅刻</option>
        <option value="早退"<?php if ($category === '早退') echo ' selected'; ?>>早退</option>
      </select>
      <br>

      <button type="submit">検索</button>
    </form>
     <p id="button"><a href="../top.php" id="topBack">TOPへ戻る</a></p>
    <!-- 検索結果の表示 -->
    <?php if (empty($results)) : ?>
      <p class="message">現在該当する申請、連絡はありません</p>
    <?php else : ?>
    <table>
      <thead>
        <tr>
          <th>申請年月日</th>
          <th>申請希望日</th>
          <th>期間</th>
          <th>区分</th>
          <th>詳細</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($results as $result) : ?>
          <tr>
            <!-- 表示を〜年〜月〜日 -->
            <td><?php echo htmlspecialchars(date('Y年n月j日 H:i', strtotime($result['registered_time'])), ENT_QUOTES); ?></td>
            <!-- 表示を〜月〜日 -->
            <td><?php echo htmlspecialchars(date('n月j日', strtotime($result['request_date_start'])), ENT_QUOTES); ?> 〜 <?php echo htmlspecialchars(date('n月j日', strtotime($result['request_date_end'])), ENT_QUOTES); ?></td>
            <td><?php echo htmlspecialchars($result['duration'], ENT_QUOTES); ?> 日</td>
            <td><?php echo htmlspecialchars($result['category'], ENT_QUOTES); ?></td>
            <td><a href="#" onclick="openModal('<?php echo htmlspecialchars($result['remarks'], ENT_QUOTES); ?>')">詳細</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <!-- モーダル -->
    <div id="myModal" class="modal">
      <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <p id="modalContent"></p>
      </div>
    </div>
    <?php endif; ?>
  </main>
  <footer>
    <p>Copytifht the one which provides A to Z about programming</p>
  </footer>
  <script>
    function openModal(content) {
      document.getElementById('modalContent').textContent = content;
      document.getElementById('myModal').style.display = 'block';
    }

    function closeModal() {
      document.getElementById('myModal').style.display = 'none';
    }
  </script>
  <footer>Copytifht  is the one which provides A to Z about programming</footer>
  <script type="text/javascript" src="../js/common.js"></script>
</body>
</html>
