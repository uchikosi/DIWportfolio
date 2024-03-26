
<?php
session_start();

// データベース接続
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'root';
$db_name = 'AttendanceManagement';

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
var_dump($_SESSION);
$update_id = $_GET['id'];
$_SESSION['update_id'] = $_GET['id'];

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

// ユーザーデータ取得
if (isset($_SESSION['update_id'])) {
    $updateId = $_SESSION['update_id'];

    // ユーザーデータを取得するクエリを実行
    $query = "SELECT * FROM users WHERE id = $updateId";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    } else {
        die("ユーザーデータが見つかりません");
    }
} else {
    die("ユーザーIDがセットされていません");
}

?>
<link rel="stylesheet" type="text/css" href="../css/common.css">
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

<form method='post' action='userUpdateConfirm.php'>
        <div>
          <input type='hidden' name='update_id' value='<?php echo $update_id; ?>'>
        </div>

<div>
    <label for='family_name'>名前(姓)</label>
    <input type='text' name='familyName' id="familyName" maxlength="10" autofocus placeholder="漢字orひらがな" oninput="validateName(this, true)" value='<?php echo htmlspecialchars($userData['family_name'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='last_name'>名前(名)</label>
    <input type='text' name='lastName' id="lastName" maxlength="10" autofocus placeholder="漢字orひらがな" oninput="validateName(this, true)" value='<?php echo htmlspecialchars($userData['last_name'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='family_name_kana'>カナ(姓)</label>
    <input type='text' id="familyNameKana" name="familyNameKana" maxlength="10" oninput="validateNameKana(this, true)" placeholder="カタカナ" value='<?php echo htmlspecialchars($userData['family_name_kana'], ENT_QUOTES); ?>'>
<div>

<div>
    <label for='last_name_kana'>カナ(名)</label>
    <input type='text' id="lastNameKana" name="lastNameKana" maxlength="10" oninput="validateNameKana(this, false)" placeholder="カタカナ" value='<?php echo htmlspecialchars($userData['last_name_kana'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='mail'>メールアドレス</label>
    <input type='text' id="mail" name="mail" maxlength="100" oninput="validateEmail(this)" placeholder="@,ドット,半角英数字のみ" value='<?php echo htmlspecialchars($userData['mail'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='password'>パスワード:</label>
    <input type='password' id="password" name="password" oninput="validatePassword(this)" placeholder="半角英数字 3~10文字" >
</div>

<div>
    <label>性別</label>
    <label><input type='radio' id="male" name='gender' value='0' <?php echo ($userData['gender'] == 0 ? 'checked' : ''); ?>> 男性</label>
    <label><input type='radio' id="female" name='gender' value='1' <?php echo ($userData['gender'] == 1 ? 'checked' : ''); ?>> 女性</label>
</div>

<div>
    <label for='postal_code'>郵便番号:</label>
    <input type='text' id="postalCode" name="postalCode" maxlength="7" pattern="^[0-9]+$" required placeholder="半角英数字" value='<?php echo htmlspecialchars($userData['postal_code'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='address'>住所:</label>
    <input type='text' id="address" name="address" maxlength="100" required placeholder="日本語で入力"oninput="validateAddress(this)" value='<?php echo htmlspecialchars($userData['address'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='company_name'>勤務先会社名:</label>
    <input type='text' id="companyName" name="companyName" maxlength="100" required placeholder="会社名を入力してください" value='<?php echo htmlspecialchars($userData['company_name'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='business'>担当業務:</label>
    <input type='text' id="business" name="business" maxlength="100" required placeholder="業務内容を入力してください" value='<?php echo htmlspecialchars($userData['work'], ENT_QUOTES); ?>'>
</div>

<div>
    <label for='staff_code'>スタッフコード:</label>
    <input type='text' id="staffCode" name="staffCode" maxlength="20" required placeholder="スタッフコードを入力してください" value='<?php echo htmlspecialchars($userData['staff_code'], ENT_QUOTES); ?>'>
</div>


<div>
    <label for='authority'>アカウント権限</label>
    <select id="authority" name="authority" required>
        <option value='0' <?php echo ($userData['authority'] == 0 ? 'selected' : ''); ?>>一般</option>
        <option value='1' <?php echo ($userData['authority'] == 1 ? 'selected' : ''); ?>>管理者</option>
    </select>
</div>

        <input type='submit' value='確認する'>
        <?php
          // エラーメッセージの表示
          if (isset($_SESSION['error'])) {
            echo "<div style='color: red; font-size: 18px;'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']); // エラーメッセージを表示した後はセッションから削除する
          }
        ?>
      </form>
    </div>
  </main>
  <footer>
    <p>Copytifht D.I.Worksl D.I.blog is the one which provides A to Z about programming</p>
  </footer>
