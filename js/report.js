function validateForm() {
  var name = document.getElementById('name').value;
  var nameKana = document.getElementById('name_kana').value;
  var staffCode = document.getElementById('staff_code').value;

  var nameRegex = /^[\u3040-\u309F\u30A0-\u30FF\u4E00-\u9FAF]+$/; // 漢字、カタカナ、ひらがなのみ
  var kanaRegex = /^[\u3040-\u309F\u30A0-\u30FF]+$/; // ひらがな、カタカナのみ
  var staffCodeRegex = /^\d+$/; // 半角数字のみ

  if (name.trim() !== '') {
    if (!nameRegex.test(name)) {
      alert('名前は漢字、カタカナ、ひらがなのみ入力してください');
      return false;
    }
  }
  if (nameKana.trim() !== '') {
    if (!kanaRegex.test(nameKana)) {
      alert('名前（カナ）はひらがな、カタカナのみ入力してください');
      return false;
    }
  }
  if (staffCode.trim() !== '') {
    if (!staffCodeRegex.test(staffCode)) {
      alert('スタッフコードは半角数字のみ入力してください');
      return false;
    }
  }
  return true;
}
