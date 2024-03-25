<?php
session_start();

// POST データから値を取得
$staff_code = $_POST['staff_code'];
$request_date_start = $_POST['request_date_start'];
$request_date_end = $_POST['request_date_end'];
$category = $_POST['category'];
$remarks = $_POST['remarks'];
$name = $_POST['name'];
$name_kana = $_POST['name_kana'];

  // もしログインしていなければ、ログインページにリダイレクト
  if (!isset($_SESSION['mail'])) {
    header("Location: login.php");
    exit();
  }

  // ユーザーの権限を取得
  $role = $_SESSION['role'] ?? null;

  // 送信されたデータから日数を計算
$start_date = $_POST['request_date_start'];
$end_date = $_POST['request_date_end'];

$start_timestamp = strtotime($start_date);
$end_timestamp = strtotime($end_date);

$difference_in_days = floor(($end_timestamp - $start_timestamp) / (60 * 60 * 24)) + 1;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="css/">
  <title>スタッフデータ入力確認画面</title>
</head>
<body>
  <header>
    <div>

      <p><a href="logout.php">Logout</a></p>
    </div>

    <div id="menu">

    </div>
  </header>
  <main>
    <div class="main">
      <h1>休日申請入力確認</h1>
      <table>
        <tr>
          <td>名前</td>
          <td><?php echo $name; ?></td>
        </tr>
        <tr>
          <td>名前（カナ）</td>
          <td><?php echo $name_kana; ?></td>
        </tr>
        <tr>
          <td>スタッフコード</td>
          <td><?php echo $staff_code; ?></td>
        </tr>
          <tr>
          <td>申請年月日</td>
          <td><?php echo $request_date_start ,'~', $request_date_end; ?></td>
        </tr>
        <tr>
          <td>選択された日数</td>
          <td><?php echo $difference_in_days; ?> 日間</td>
        <tr>
        <td>区分</td>
            <td><?php echo $category; ?></td>
        </tr>
        <tr>
          <td>備考</td>
          <td><?php echo $remarks; ?></td>
        </tr>
      </table>

      <div class="button-container">
        <form method="post" action="holidayRequest.php" id="registBack">
          <!-- hiddenフィールドの値をフォームに戻す -->
          <input type="hidden" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="name_kana" value="<?php echo isset($_POST['name_kana']) ? htmlspecialchars($_POST['name_kana'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="staff_code" value="<?php echo isset($_POST['staff_code']) ? htmlspecialchars($_POST['staff_code'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="request_date_start" value="<?php echo isset($_POST['request_date_start']) ? htmlspecialchars($_POST['request_date_start'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="request_date_end" value="<?php echo isset($_POST['request_date_end']) ? htmlspecialchars($_POST['request_date_end'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="category" value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="remarks" value="<?php echo isset($_POST['remarks']) ? htmlspecialchars($_POST['remarks'], ENT_QUOTES) : ''; ?>">
          <button type="submit">入力を修正する</button>
        </form>

        <!-- 登録処理をしてアカウント登録完了画面に遷移 -->
        <form method="post" action="holidayRequestComplete.php" id="registerDatabase">
          <!-- 各確認要素 -->
          <button type="submit">休日を申請</button>
         <input type="hidden" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="name_kana" value="<?php echo isset($_POST['name_kana']) ? htmlspecialchars($_POST['name_kana'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="staff_code" value="<?php echo isset($_POST['staff_code']) ? htmlspecialchars($_POST['staff_code'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="request_date_start" value="<?php echo isset($_POST['request_date_start']) ? htmlspecialchars($_POST['request_date_start'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="request_date_end" value="<?php echo isset($_POST['request_date_end']) ? htmlspecialchars($_POST['request_date_end'], ENT_QUOTES) : ''; ?>">
           <input type="hidden" name="difference_in_days" value="<?php echo isset($_POST['difference_in_days']) ? htmlspecialchars($_POST['difference_in_days'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="category" value="<?php echo isset($_POST['category']) ? htmlspecialchars($_POST['category'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="remarks" value="<?php echo isset($_POST['remarks']) ? htmlspecialchars($_POST['remarks'], ENT_QUOTES) : ''; ?>">
        </form>
      </div>
    </div>
  </main>
  <footer>
    <p>Copytifht the one which provides A to Z about programming</p>
  </footer>
</body>
</html>
