<main>
    <div id="regist">
      <h1>スタッフ登録フォーム</h1>
      <form method="post" action="userRegister_confirm.php" onsubmit="return validateForm()">
        <label for="familyName">名前（姓）:</label>
        <input type="text" id="familyName" name="familyName" maxlength="10" autofocus oninput="validateName(this, true)" placeholder="漢字orひらがな"
          <?php if (isset($_POST['familyName'])) echo 'value="' . htmlspecialchars($_POST['familyName'], ENT_QUOTES) . '"'; ?>>
        <br>

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
        <input type="text" id="postalCode" name="postalCode" maxlength="8"  required placeholder="半角英数字" <?php if (isset($_POST['postalCode'])) echo 'value="' . htmlspecialchars($_POST['postalCode'], ENT_QUOTES) . '"'; ?>>
        <br>

        <!-- 住所 -->
        <label for="address">住所:</label>
        <input type="text" id="address" name="address" maxlength="100" required placeholder="日本語で入力"oninput="validateAddress(this)" <?php if (isset($_POST['address'])) echo 'value="' . htmlspecialchars($_POST['address'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="company_name">勤務先会社名:</label>
        <input type="text" id="company_name" name="company_name" maxlength="50" required placeholder=""oninput="validateAddress(this)" <?php if (isset($_POST['company_name'])) echo 'value="' . htmlspecialchars($_POST['company_name'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="business">担当業務:</label>
        <input type="text" id="business" name="business" maxlength="50"  placeholder=""oninput="validateAddress(this)" <?php if (isset($_POST['business'])) echo 'value="' . htmlspecialchars($_POST['business'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="staff_code">スタッフコード:</label>
        <input type="text" id="staff_code" name="staff_code" maxlength="6" required placeholder=""oninput="validateAddress(this)" <?php if (isset($_POST['staff_code'])) echo 'value="' . htmlspecialchars($_POST['staff_code'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="image">証明写真:</label>
        <input type="file" id="image" name="image" accept=".jpg, .jpeg, .png, .gif" placeholder="画像選択してください">
        <br>

        <label for="remarks">備考:</label>
        <input type="text" id="remarks" name="remarks" maxlength="1000" placeholder=""oninput="validateAddress(this)" <?php if (isset($_POST['remarks'])) echo 'value="' . htmlspecialchars($_POST['remarks'], ENT_QUOTES) . '"'; ?>>
        <br>

        <label for="authority">アカウント権限:</label>
        <select id="authority" name="authority" required>
            <option value="0" <?php if (isset($_POST['authority']) && $_POST['authority'] == '0') echo 'selected'; ?>>一般</option>
            <option value="1" <?php if (isset($_POST['authority']) && $_POST['authority'] == '1') echo 'selected'; ?>>管理者</option>
        </select>
        <br>

        <button type="submit">確認する</button>
      </form>
    </div>
  </main>
  <footer>
    <p>Copytifht the one which provides A to Z about programming</p>
  </footer>
