<?php

session_start();

if ($_SESSION['auth'] !== true) {
    header('Location: index.php');
    exit;
}

// ログイン後のページ（パスワード変更画面）を開く
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $csrfToken = bin2hex(random_bytes(24)); // トークンを生成
    $_SESSION["token"] = $csrfToken;
}

// パスワード変更処理
if ($_SERVER["REQUEST_METHOD"] === "POST" && $_GET["action"] === "updatePassword") {

    if (empty($_SESSION["token"]) || $_SESSION["token"] !== $_POST["token"]) {
        die("不正なリクエストです。");
    }

    try {
        $dbh = getDBConnection();

        $sql = "UPDATE ";
        $sql .= " users ";
        $sql .= " SET ";
        $sql .= " encrypted_password = :password ";
        $sql .= " WHERE ";
        $sql .= " id = :id ";

        $stmt = $dbh->prepare($sql);
        $result = $stmt->execute([
            "password" => encrypt($_POST["password"]),
            "id" => $_SESSION["id"]
        ]);

        if ($result) {
            $_SESSION = [];
            session_destroy();
            header('Location: index.php?passwordUpdateCompleted=true');
            exit;
        }
    } catch(PDOException $e) {
        echo $e->getMessage();
        die();
    }
}

function getDBConnection() {
    $dsn = 'mysql:dbname=app;host=db;charset=utf8';
    $user = 'root';
    $password = '';
    return new PDO($dsn, $user, $password);
}

function encrypt($password) {
    return $password;
}

?>

<html>
<meta charset="UTF-8">
<head>
    <title>メニュー</title>
</head>
<body>
ログインしました！🎉
ようこそ <?php echo htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") ?>さん<br>
<form action="./index.php?action=logout" method="post">
    <input type="submit" name="logout" value="ログアウト" />
</form>
<form action="./menu.php?action=updatePassword" method="post">
    <input type="hidden" name="token" value="<?php echo htmlspecialchars($csrfToken, ENT_QUOTES, "UTF-8") ?>" />
    <input type="password" name="password" value="" />
    <input type="submit" name="logout" value="パスワード変更" />
</form>
</body>
</html>
