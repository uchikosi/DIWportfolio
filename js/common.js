// ページが読み込まれた後にリンクを追加する
window.onload = function () {
  var heading = document.getElementById('mainTitole'); // h1要素を取得
  var link = document.createElement('a'); // a要素を作成
  link.href = 'http://localhost:8888/AttendanceManagementSystem/top.php'; // リンク先を設定
  link.textContent = heading.textContent; // リンクのテキストをh1要素のテキストと同じに設定
  heading.textContent = ''; // h1要素のテキストをクリア
  heading.appendChild(link); // h1要素にリンクを追加
};
