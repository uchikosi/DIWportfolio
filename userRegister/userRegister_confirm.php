
<?php
session_start();

// POST データから値を取得
$familyName = $_POST['familyName'];
$lastName = $_POST['lastName'];
$familyNameKana = $_POST['familyNameKana'];
$lastNameKana = $_POST['lastNameKana'];
$mail = $_POST['mail'];
$password = $_POST['password'];
$gender = ($_POST['gender'] == '0') ? '男' : '女';
// 三項演算子if ($_POST['gender'] == '0') {$gender = '男';} else {$gender = '女';}
$postalCode = $_POST['postalCode'];
$address = $_POST['address'];
$company_name = $_POST['company_name'];
$business = $_POST['business'];
$staff_code = $_POST['staff_code'];
$image = $_POST['image'];
$remarks = $_POST['remarks'];
$authority = ($_POST['authority'] == '0') ? '一般' : '管理者';

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
  <link rel="stylesheet" type="text/css" href="../css/common.css">
  <title>スタッフデータ入力確認画面</title>
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
    <div class="main">
      <h1>スタッフデータ入力確認</h1>
      <table>
        <tr>
          <td>名前（姓）</td>
          <td><?php echo $familyName; ?></td>
        </tr>
        <tr>
          <td>名前（名）</td>
          <td><?php echo $lastName; ?></td>
        </tr>
        <tr>
          <td>カナ（姓）</td>
          <td><?php echo $familyNameKana; ?></td>
        </tr>
        <tr>
          <td>カナ（名）</td>
          <td><?php echo $lastNameKana; ?></td>
        </tr>
        <tr>
          <td>メールアドレス</td>
          <td><?php echo $mail; ?></td>
        </tr>
        <tr>
          <td>パスワード</td>
          <td><?php echo str_repeat("●", strlen($password)); ?></td>
        </tr>
        <tr>
          <td>性別</td>
          <td><?php echo $gender; ?></td>
        </tr>
        <tr>
          <td>郵便番号</td>
          <td><?php echo $postalCode; ?></td>
        </tr>
        <tr>
          <td>住所</td>
          <td><?php echo $address; ?></td>
        </tr>
        <tr>
          <td>勤務先会社名</td>
          <td><?php echo $company_name; ?></td>
        </tr>
          <tr>
          <td>担当業務</td>
          <td><?php echo $business; ?></td>
        </tr>
          <tr>
          <td>スタッフコード</td>
          <td><?php echo $staff_code; ?></td>
        <!-- </tr>
          <tr>
          <td>写真</td>
          <td><?php //echo $image; ?></td>
        </tr>
        <tr>
          <td>備考</td>
          <td><?php //echo $remarks; ?></td>
        </tr> -->
        <tr>
          <td>アカウント権限</td>
          <td><?php echo $authority; ?></td>
        </tr>
      </table>

      <div class="button-container">
        <form method="post" action="userRegister.php" id="registBack">
          <!-- hiddenフィールドの値をフォームに戻す -->
          <input type="hidden" name="familyName" value="<?php echo isset($_POST['familyName']) ? htmlspecialchars($_POST['familyName'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="lastName" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="familyNameKana" value="<?php echo isset($_POST['familyNameKana']) ? htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="lastNameKana" value="<?php echo isset($_POST['lastNameKana']) ? htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="mail" value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="gender" value="<?php echo isset($_POST['gender']) ? htmlspecialchars($_POST['gender'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="postalCode" value="<?php echo isset($_POST['postalCode']) ? htmlspecialchars($_POST['postalCode'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="company_name" value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="business" value="<?php echo isset($_POST['business']) ? htmlspecialchars($_POST['business'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="staff_code" value="<?php echo isset($_POST['staff_code']) ? htmlspecialchars($_POST['staff_code'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="image" value="<?php echo isset($_POST['image']) ? htmlspecialchars($_POST['image'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="remarks" value="<?php echo isset($_POST['remarks']) ? htmlspecialchars($_POST['remarks'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="authority" value="<?php echo isset($_POST['authority']) ? htmlspecialchars($_POST['authority'], ENT_QUOTES) : ''; ?>">
          <button type="submit">前に戻る</button>
        </form>

        <!-- 登録処理をしてアカウント登録完了画面に遷移 -->
        <form method="post" action="userRegister_complete.php" id="registerDatabase">
          <!-- 各確認要素 -->
          <button type="submit">登録する</button>
          <input type="hidden" name="familyName" value="<?php echo isset($_POST['familyName']) ? htmlspecialchars($_POST['familyName'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="lastName" value="<?php echo isset($_POST['lastName']) ? htmlspecialchars($_POST['lastName'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="familyNameKana" value="<?php echo isset($_POST['familyNameKana']) ? htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="lastNameKana" value="<?php echo isset($_POST['lastNameKana']) ? htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="mail" value="<?php echo isset($_POST['mail']) ? htmlspecialchars($_POST['mail'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="password" value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="gender" value="<?php echo isset($_POST['gender']) ? htmlspecialchars($_POST['gender'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="postalCode" value="<?php echo isset($_POST['postalCode']) ? htmlspecialchars($_POST['postalCode'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="company_name" value="<?php echo isset($_POST['company_name']) ? htmlspecialchars($_POST['company_name'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="business" value="<?php echo isset($_POST['business']) ? htmlspecialchars($_POST['business'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="staff_code" value="<?php echo isset($_POST['staff_code']) ? htmlspecialchars($_POST['staff_code'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="image" value="<?php echo isset($_POST['image']) ? htmlspecialchars($_POST['image'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="remarks" value="<?php echo isset($_POST['remarks']) ? htmlspecialchars($_POST['remarks'], ENT_QUOTES) : ''; ?>">
          <input type="hidden" name="authority" value="<?php echo isset($_POST['authority']) ? htmlspecialchars($_POST['authority'], ENT_QUOTES) : ''; ?>">
        </form>
      </div>
    </div>
  </main>
  <footer>
    <p>Copytifht the one which provides A to Z about programming</p>
  </footer>
</body>
</html>
