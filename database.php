try {
    $pdo = new PDO("mysql:host=$host;port=3306", $username, $password);
    echo "LOGIN OK";
    exit;
} catch (PDOException $e) {
    die($e->getMessage());
}
