<?php
$dsn = 'mysql:dbname=php_db_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);
    //昇順・降順操作
    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = NULL;
    }

    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
    } else {
        $keyword = NULL;
    }

    if ($order === 'desc') {
        $sql_select = 'SELECT * FROM products WHERE product_name LIKE :keyword ORDER BY updated_at DESC';
    } else {
        $sql_select = 'SELECT * FROM products WHERE product_name LIKE :keyword ORDER BY updated_at ASC';
    }

    //実行  
    $stmt_select = $pdo->prepare($sql_select);

    $partial_match = "%{$keyword}%";

    $stmt_select->bindValue(':keyword', $partial_match, PDO::PARAM_STR);

    $stmt_select->execute();

    //配列での取得
    $products = $stmt_select->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    exit($e->getMessage());
}





?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>在庫一覧</title>
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
        <article class="products">
            <h1>在庫一覧</h1>
            <?php
            if (isset($_GET['message'])) {
                echo "<p class='succes'>{$_GET['message']}</p>";
            }
            ?>

            <div class="products-ui">
                <div>
                    <!-- 並び替えボタンと検索ボックス -->
                    <a href="read.php?order=desc&keyword=<?= $keyword ?>">
                        <img src="images/up.png" alt="昇順に並べる" class="sort-img">
                    </a>
                    <a href="read.php?order=asc&keyword=<?= $keyword ?>">
                        <img src="images/down.png" alt="降順に並べる" class="sort-img">
                    </a>

                    <form action="read.php" method="get" class="search-form">
                        <input type="hidden" name="order" value="<?= $order ?>">
                        <input type="text" class="search-box" placeholder="商品名で検索" name="keyword" value="<?= $keyword ?>">
                        <input type="submit" value="検索">
                        <a href="read.php" style="text-decoration:none; color:black;">一覧へ
                        </a>
                    </form>
                </div>
                <a href="create.php" class="btn">商品登録</a>
            </div>
            <table class="products-table">
                <tr>
                    <th>商品コード</th>
                    <th>商品名</th>
                    <th>単価</th>
                    <th>在庫数</th>
                    <th>仕入先コード</th>
                    <th>編集</th>
                </tr>
                <?php
                // 配列の中身を順番に取り出し、表形式で出力する
                foreach ($products as $product) {
                    $table_row = "
                         <tr>
                         <td>{$product['product_code']}</td>
                         <td>{$product['product_name']}</td>
                         <td>{$product['price']}</td>
                         <td>{$product['stock_quantity']}</td>
                         <td>{$product['vendor_code']}</td>
                         <td><a href='update.php?id={$product['id']}'><img src='images/edit.png' alt='編集' class='edit-icon'></a></td>
                         <td><a href='delete.php?id={$product['id']}'><img src='images/delete.png' alt='削除' class='delete-icon'></td> 
                         </tr>                    
                     ";
                    echo $table_row;
                }
                ?>
            </table>
        </article>
    </main>
    <footer>
        <p class="copyright">&copy; 在庫管理システム　むとまる</p>
    </footer>
</body>

</html>