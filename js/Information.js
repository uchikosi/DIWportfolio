window.onload = function () {
  document.getElementById('registrationInformationForm').addEventListener('submit', function (event) {
    var fields = [
      { id: 'familyName', required: true, message: '名前（姓）は入力してください' },
      { id: 'lastName', required: true, message: '名前（名）は入力してください' },
      { id: 'familyNameKana', required: true, message: 'カナ（姓）は入力してください' },
      { id: 'lastNameKana', required: true, message: 'カナ（名）は入力してください' },
      { id: 'mail', required: true, message: 'メールアドレスは入力してください' },
      { id: 'postalCode', required: true, message: '郵便番号は入力してください' },
      { id: 'address', required: true, message: '住所は入力してください' }
    ];

    var errorMessage = '';

    fields.forEach(function (field) {
      var element = document.getElementById(field.id);
      if (field.required && element.value === '') {
        errorMessage += field.message + '\n';
      }
    });

    if (errorMessage !== '') {
      alert(errorMessage.trim());
      event.preventDefault(); // フォームの送信をキャンセル
    }
  });
};

function validateForm() {
  var familyName = document.getElementById('familyName').value;
  var lastName = document.getElementById('lastName').value;
  var familyNameKana = document.getElementById('familyNameKana').value;
  var lastNameKana = document.getElementById('lastNameKana').value;
  var mail = document.getElementById('mail').value;
  var postalCode = document.getElementById('postalCode').value;
  var address = document.getElementById('address').value;

  var nameRegex = /^[\u3040-\u309F\u30A0-\u30FF\u4E00-\u9FAF]+$/; // 漢字、カタカナ、ひらがなのみ
  var kanaRegex = /^[\u3040-\u309F\u30A0-\u30FF]+$/; // ひらがな、カタカナのみ
  var emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/; // メールアドレス
  var postalCodeRegex = /^\d{3}-?\d{4}$/; // 郵便番号
  var addressRegex = /^[^\x01-\x7E\xA1-\xDF]+$/; // 全角全て可能、記号

  if (!nameRegex.test(familyName)) {
    alert('名前（姓）は漢字、カタカナ、ひらがなのみ入力してください');
    return false;
  }
  if (!nameRegex.test(lastName)) {
    alert('名前（名）は漢字、カタカナ、ひらがなのみ入力してください');
    return false;
  }
  if (!kanaRegex.test(familyNameKana)) {
    alert('カナ（姓）はひらがな、カタカナのみ入力してください');
    return false;
  }
  if (!kanaRegex.test(lastNameKana)) {
    alert('カナ（名）はひらがな、カタカナのみ入力してください');
    return false;
  }
  if (!emailRegex.test(mail)) {
    alert('有効なメールアドレスを入力してください');
    return false;
  }
  if (!postalCodeRegex.test(postalCode)) {
    alert('郵便番号は半角数字、ハイフンを含む形式で入力してください');
    return false;
  }
  if (!addressRegex.test(address)) {
    alert('住所は全角文字および記号のみ入力してください');
    return false;
  }

  return true;
}
