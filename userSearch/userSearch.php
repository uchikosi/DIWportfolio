<?php
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

  // データベースからユーザー情報を取得（idの大きい順に並べる）
  $sql = "SELECT * FROM users ORDER BY id DESC";
  $result = $pdo->query($sql);

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
  <link rel="stylesheet" type="text/css" href="css/searc.css">
  <title>ユーザー一覧</title>
  <style>
    /* 罫線 */
    table {
      border-collapse: collapse;
      width: 98%;
      margin: 15px;
    }

    table, th, td {
      border: 1px solid black;
      text-align: center; /* 中央配置 */
      padding: 8px; /* セル内の余白を追加 */
    }
  </style>
</head>
<body>
  <header>
    <div>
      <p>ようこそ ID  <?php echo $user_id; ?>様</p>
      <p> <?php echo $_SESSION['mail']; ?></p>
      <?php if ($role === '一般'): ?>
        <p>このアカウント権限は一般です</p>
      <?php elseif ($role === '管理者'): ?>
        <p>このアカウント権限は管理者です</p>
      <?php endif; ?>
      <p><a href="logout.php">Logout</a></p>
    </div>

    <div id="menu">
      <ul>
      </ul>
    </div>
  </header>
  <main>
     <h3>従業員一覧</h3>
    <div>
      <form method="GET" action="userSearch.php">
        <div>
          <label for="family_name">名前（姓）:</label>
          <input type="text" id="family_name" name="family_name" value=""><br>
          <label for="last_name">名前（名）:</label>
          <input type="text" id="last_name" name="last_name" value=""><br>
        </div>

        <div>
          <label for="family_name_kana">カナ（姓）:</label>
          <input type="text" id="family_name_kana" name="family_name_kana" value=""><br>
          <label for="last_name_kana">カナ（名）:</label>
          <input type="text" id="last_name_kana" name="last_name_kana" value=""><br>
        </div>

        <div>
          <label for="mail">メールアドレス:</label>
          <input type="text" id="mail" name="mail" value=""><br>

          <input type="radio" id="male" name="gender" value="0">
          <label for="male">男</label>
          <input type="radio" id="female" name="gender" value="1">
          <label for="female">女</label><br>

          <label for="authority">アカウント権限:</label>
          <select id="authority" name="authority">
            <option value="" selected>全て</option>
            <option value="0">一般</option>
            <option value="1">管理者</option>
          </select><br>
        </div>

        <button type="submit">検索</button>
      </form>
    </div>

    <?php
    // 検索処理
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
      // クエリパラメータを取得
      $family_name = $_GET["family_name"] ?? "";
      $last_name = $_GET["last_name"] ?? "";
      $family_name_kana = $_GET["family_name_kana"] ?? "";
      $last_name_kana = $_GET["last_name_kana"] ?? "";
      $email = $_GET["mail"] ?? "";
      $gender = $_GET["gender"] ?? "";
      $authority = $_GET["authority"] ?? "";

      // SQLクエリを構築
      $sql = "SELECT * FROM users WHERE 1=1";
      if (!empty($family_name)) {
        $sql .= " AND family_name LIKE '%$family_name%'";
      }
      if (!empty($last_name)) {
        $sql .= " AND last_name LIKE '%$last_name%'";
      }
      if (!empty($family_name_kana)) {
        $sql .= " AND family_name_kana LIKE '%$family_name_kana%'";
      }
      if (!empty($last_name_kana)) {
        $sql .= " AND last_name_kana LIKE '%$last_name_kana%'";
      }
      if (!empty($email)) {
        $sql .= " AND mail LIKE '%$email%'";
      }
      if ($gender !== "") {
        $sql .= " AND gender = '$gender'";
      }

      // アカウント権限が「一般」の場合も条件に追加
      if ($authority !== "") {
        $sql .= " AND authority = '$authority'";
      }

      // IDを降順で並び替える
      $sql .= " ORDER BY id DESC";
    }
    // データベースクエリ実行処理
    try {
      $stmt = $pdo->query($sql);
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      die("クエリ実行エラー: " . $e->getMessage());
    }
    ?>

    <?php if ($_SERVER["REQUEST_METHOD"] == "GET" && !isset($_GET['family_name']) && !isset($_GET['last_name']) && !isset($_GET['family_name_kana']) && !isset($_GET['last_name_kana']) && !isset($_GET['mail']) && !isset($_GET['gender']) && !isset($_GET['authority'])): ?>
    <!-- <p>検索を行ってください。</p> -->
    <?php else: ?>
      <div id="list">
        <table>
          <tr>
            <th>ID</th>
            <th>名前（姓）</th>
            <th>名前（名）</th>
            <th>カナ（姓）</th>
            <th>カナ（名）</th>
            <th>メールアドレス</th>
            <th>性別</th>
            <th>アカウント権限</th>
            <th>削除フラグ</th>
            <th>登録日時</th>
            <th>更新日時</th>
            <?php if ($role === '管理者'): ?>
              <th>更新</th>
              <th>削除</th>
            <?php endif; ?>
          </tr>

          <?php
            // データベースから取得したユーザー情報を表示
            if (empty($result)) {
              echo "<tr><td colspan='15'>該当するデータはありません。</td></tr>";
            } else {
            foreach ($result as $row) {
              echo "<tr>";
              echo "<td>{$row['id']}</td>";
              echo "<td>{$row['family_name']}</td>";
              echo "<td>{$row['last_name']}</td>";
              echo "<td>{$row['family_name_kana']}</td>";
              echo "<td>{$row['last_name_kana']}</td>";
              // メールアドレスが50文字以上の場合に折り返して表示
              $mail = $row['mail'];
              $mailClass = strlen($mail) > 50 ? 'long-text' : ''; // 適切な文字数を設定
              echo "<td class='$mailClass'>{$mail}</td>";
              echo "<td>" . ($row['gender'] == 0 ? '男' : '女') . "</td>";
              echo "<td>" . ($row['authority'] == 0 ? '一般' : '管理者') . "</td>";
              echo "<td>" . ($row['delete_flag'] == 0 ? '有効' : '無効') . "</td>";
              // 登録日時
              echo "<td>" . date("Y/m/d", strtotime($row['registered_time'])) . "</td>";
              // 更新日時
              echo "<td>";
              if ($row['update_time'] !== null) {
                // 値があれば表示、NULLの場合に更新なしになる
                echo date("Y/m/d", strtotime($row['update_time']));
              }else {
                echo "更新なし";
              }
              echo "</td>";
              if ($role === '管理者'):
                echo "<td><a href='update/update.php?id={$row['id']}'>更新</a></td>";
                echo "<td><a href='delete/delete.php?id={$row['id']}'>削除</a></td>";
              endif;
              echo "</tr>";
            }
          }
          ?>
        </table>
      </div>
    <?php endif; ?>
  </main>
  <footer>
    <p>Copytifht  the one which provides A to Z about programming</p>
  </footer>
  <?php
    // データベース接続を閉じる
    $pdo = null;
  ?>
  <script>
    var longTextElements = document.getElementsByClassName('long-text');
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
