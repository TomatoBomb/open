<?PHP
$dsn = 'mysql:dbname=php_db_app;host=localhost;charset=utf8mb4';
$user = 'root';
$password = '';

try {
    $pdo = new PDO($dsn, $user, $password);

    $sql_delete = 'DELETE FROM products WHERE id = :id';
    $stmt_delete = $pdo->prepare($sql_delete);

    // バインド
    $stmt_delete->bindValue(':id', $_GET['id'], PDO::PARAM_INT);

    // SQL文を実行する
    $stmt_delete->execute();

    // 削除した件数を取得する

    $message = "商品を削除しました。";

    // リダイレクト+メッセージ
    header("Location: read.php?message={$message}");
} catch (PDOException $e) {
    exit($e->getMessage());
}
