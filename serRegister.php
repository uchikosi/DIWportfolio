<main>
    <?php if ($role === '管理者'): ?>
    <div id="regist">
      <h1>アカウント登録フォーム</h1>
      <form method="post" action="userRegist_confirm.php" onsubmit="return validateForm()">
        <label for="familyName">名前（姓）:</label>
        <input type="text" id="familyName" name="familyName" maxlength="10" autofocus oninput="validateName(this, true)" placeholder="漢字orひらがな"
          <?php if (isset($_POST['familyName'])) echo 'value="' . htmlspecialchars($_POST['familyName'], ENT_QUOTES) . '"'; ?>>
        <br>
        <!-- validateName(this, true)"　validateNameの引数をLastNameになっているためelseの時に発火する -->

        <label for="lastName">名前（名）:</label>
        <input type="text" id="lastName" name="lastName" maxlength="10" autofocus oninput="validateName(this, false)"  placeholder="漢字orひらがな"
          <?php if (isset($_POST['lastName'])) echo 'value="' . htmlspecialchars($_POST['lastName'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="familyNameKana">カナ（姓）:</label>
        <input type="text" id="familyNameKana" name="familyNameKana" maxlength="10" oninput="validateNameKana(this, true)" placeholder="カタカナ" <?php if (isset($_POST['familyNameKana'])) echo 'value="' . htmlspecialchars($_POST['familyNameKana'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="lastNameKana">カナ（名）:</label>
        <input type="text" id="lastNameKana" name="lastNameKana" maxlength="10" oninput="validateNameKana(this, false)" placeholder="カタカナ" <?php if (isset($_POST['lastNameKana'])) echo 'value="' . htmlspecialchars($_POST['lastNameKana'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="mail">メールアドレス:</label>
        <input type="text" id="mail" name="mail" maxlength="100" oninput="validateEmail(this)" placeholder="@,ドット,半角英数字のみ" <?php if (isset($_POST['mail'])) echo 'value="' . htmlspecialchars($_POST['mail'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="password">パスワード:</label>
        <input type="password" id="password" name="password" minlength="3" maxlength="10" oninput="validatePassword(this)" placeholder="半角英数字 3~10文字">
        <br>

        <label>性別:</label>
        <input type="radio" id="male" name="gender" value="0" <?php if (!isset($_POST['gender']) || (isset($_POST['gender']) && $_POST['gender'] == '0')) echo 'checked'; ?>>
        <label for="male">男</label>
        <input type="radio" id="female" name="gender" value="1" <?php if (isset($_POST['gender']) && $_POST['gender'] == '1') echo 'checked'; ?>>
        <label for="female">女</label>
        <br>

        <label for="postalCode">郵便番号:</label>
        <input type="text" id="postalCode" name="postalCode" maxlength="7" pattern="^[0-9]+$" required placeholder="半角英数字" <?php if (isset($_POST['postalCode'])) echo 'value="' . htmlspecialchars($_POST['postalCode'], ENT_QUOTES) . '"'; ?>>
        <br>

        <!-- 住所（番地） -->
        <label for="address2">住所（番地）:</label>
        <input type="text" id="address2" name="address2" maxlength="100" required placeholder="日本語で入力"oninput="validateAddress(this)" <?php if (isset($_POST['address2'])) echo 'value="' . htmlspecialchars($_POST['address2'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="authority">アカウント権限:</label>
        <select id="authority" name="authority" required>
            <option value="0" <?php if (isset($_POST['authority']) && $_POST['authority'] == '0') echo 'selected'; ?>>一般</option>
            <option value="1" <?php if (isset($_POST['authority']) && $_POST['authority'] == '1') echo 'selected'; ?>>管理者</option>
        </select>
        <br>

        <!-- oninput 入力フィールドに変更を加えるたびにjsに設定したバリデーションが行われる -->
        <!-- pattern属性 それぞれの項目の入力可能な文字を制限する　正規表現 -->
        <!-- required属性　入力必須 -->
        <!-- placeholder属性　フォームに説明を記入できる -->

        <button type="submit">確認する</button>
      </form>
      <?php else: ?>
         <h1>一般の権限では登録できません。</h1>
      <?php endif; ?>
    </div>
  </main>
  <footer>
    <p>Copytifht the one which provides A to Z about programming</p>
  </footer>
