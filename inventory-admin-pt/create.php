<?PHP
$dsn = 'mysql:dbname=php_db_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

if (isset($_POST['submit'])) {
    try {
        $pdo = new PDO($dsn, $user, $password);

        $sql_insert = '
            INSERT INTO products (product_code, product_name, price, stock_quantity, vendor_code)
            VALUES (:product_code, :product_name, :price, :stock_quantity, :vendor_code)
        ';
        $stmt_insert = $pdo->prepare($sql_insert);

        //バインド
        $stmt_insert->bindValue(':product_code', $_POST['product_code'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':product_name', $_POST['product_name'], PDO::PARAM_STR);
        $stmt_insert->bindValue(':price', $_POST['price'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':stock_quantity', $_POST['stock_quantity'], PDO::PARAM_INT);
        $stmt_insert->bindValue(':vendor_code', $_POST['vendor_code'], PDO::PARAM_INT);

        // 実行する
        $stmt_insert->execute();

        // 商品一覧ページへリダイレクト+メッセージのget作っちゃう。
        $message = "商品を登録しました。";
        header("Location: read.php?message={$message}");
    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}
//仕入れ先コードの取得
try {
    $pdo = new PDO($dsn, $user, $password);

    //ベンダーコードを取得するSQL文
    $sql_select = 'SELECT vendor_code FROM vendors';

    // 実行する
    $stmt_select = $pdo->query($sql_select);

    // SQL文の実行結果を配列で取得する（1次元）
    $vendor_codes = $stmt_select->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    exit($e->getMessage());
}
?>
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品登録</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <nav>
            <a href="index.php">在庫管理システム</a>
        </nav>
    </header>
    <main>
        <article class="registration">
            <h1>商品登録</h1>
            <div class="back">
                <a href="read.php" class="btn">&lt; 戻る</a>
            </div>
            <form action="create.php" method="post" class="registration-form">
                <div>
                    <label for="product_code">商品コード</label>
                    <input type="number" name="product_code" min="0" max="100000000" required>

                    <label for="product_name">商品名</label>
                    <input type="text" name="product_name" maxlength="50" required>

                    <label for="price">単価</label>
                    <input type="number" name="price" min="0" max="100000000" required>

                    <label for="stock_quantity">在庫数</label>
                    <input type="number" name="stock_quantity" min="0" max="100000000" required>

                    <label for="vendor_code">仕入先コード</label>
                    <select name="vendor_code" required>
                        <option disabled selected value>選択してください</option>
                        <?php
                        foreach ($vendor_codes as $vendor_code) {
                            echo "<option value='{$vendor_code}'>{$vendor_code}</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="submit-btn" name="submit" value="create">登録</button>
            </form>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 在庫管理システム むとまる</p>
    </footer>
</body>

</html>