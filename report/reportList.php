<?php
// データベースに接続
$pdo = new PDO("mysql:dbname=AttendanceManagement;host=localhost;", "root", "root");

// フォームからの検索条件を取得
$name = isset($_GET['name']) ? $_GET['name'] : '';
$name_kana = isset($_GET['name_kana']) ? $_GET['name_kana'] : '';
$staff_code = isset($_GET['staff_code']) ? $_GET['staff_code'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
$registration_date = isset($_GET['registration_date']) ? $_GET['registration_date'] : '';

// SQLクエリの作成
$sql = "SELECT *, DATEDIFF(request_date_end, request_date_start) AS duration FROM holidayRequest WHERE 1=1";
$params = [];

if (!empty($name)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%$name%";
}
if (!empty($name_kana)) {
    $sql .= " AND name_kana LIKE ?";
    $params[] = "%$name_kana%";
}
if (!empty($staff_code)) {
    $sql .= " AND staff_code = ?";
    $params[] = $staff_code;
}
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
    <title>休日申請履歴</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaaaaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h2>休日申請履歴</h2>

    <!-- 検索フォーム -->
    <form method="get" action="reportList.php">
        <label for="name">名前:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name, ENT_QUOTES); ?>">

        <label for="name_kana">名前（カナ）:</label>
        <input type="text" id="name_kana" name="name_kana" value="<?php echo htmlspecialchars($name_kana, ENT_QUOTES); ?>">

        <label for="staff_code">スタッフコード:</label>
        <input type="text" id="staff_code" name="staff_code" value="<?php echo htmlspecialchars($staff_code, ENT_QUOTES); ?>">

        <label for="category">区分:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($category, ENT_QUOTES); ?>">

        <label for="registration_date">登録日:</label>
        <input type="date" id="registration_date" name="registration_date" value="<?php echo htmlspecialchars($registration_date, ENT_QUOTES); ?>">

        <button type="submit">検索</button>
    </form>

    <!-- 検索結果の表示 -->
    <table>
        <thead>
            <tr>
                <th>登録日時</th>
                <th>名前</th>
                <th>スタッフコード</th>
                <th>申請希望日</th>
                <th>期間</th>
                <th>区分</th>
                <th>詳細</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($result['registered_time'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($result['name'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($result['staff_code'], ENT_QUOTES); ?></td>
                    <td><?php echo htmlspecialchars($result['request_date_start'] . ' 〜 ' . $result['request_date_end'], ENT_QUOTES); ?></td>
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

    <script>
        function openModal(content) {
            document.getElementById('modalContent').textContent = content;
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }
    </script>

</body>

</html>
