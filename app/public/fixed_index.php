<?php

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_GET["action"] === "login") {
    $dbh = getDBConnection();

    $sql = "SELECT ";
    $sql .= " * FROM ";
    $sql .= " users ";
    $sql .= " WHERE ";
    $sql .= " email = :email ";
    $sql .= "AND encrypted_password = :encrypted_password ";

    $stmt = $dbh->prepare($sql);

    $stmt->execute(
      [
        ':email' => $_POST["email"],
        ':encrypted_password' => encrypt($_POST["password"]),
      ]
    );

    $row = $stmt->fetch();

    if ($row !== false) {
        session_start();
        session_regenerate_id(true);

        $_SESSION['auth'] = true;
        $_SESSION["email"] = $row["email"];
        $_SESSION["name"] = $row["name"];
        header('Location: menu.php');
        exit;
    } else {
        $errorMessage = "ログインに失敗しました。";
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && $_GET["action"] === "logout") {
  session_start();
  $_SESSION = [];
  session_destroy();
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
    <title>ログイン</title>
  </head>
  <body>
  　<h1>ログイン</h1>
    <form action="index.php?action=login" method="post">
      <?php if (!empty($errorMessage)) { ?>
        <span style="color: red"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES, "UTF-8") ?></span>
      <?php } ?>
      <table>
        <tr>
          <td>メールアドレス</td>
          <td><input type="text" name="email" value="<?php echo htmlspecialchars($_REQUEST["email"], ENT_QUOTES, "UTF-8") ?>"></td>
        </tr>
        <tr>
          <td>パスワード</td>
          <td><input type="password" name="password"></td>
        </tr>
      </table>
      <input type="submit" value="ログイン">
    </form>
  </body>
</html>
