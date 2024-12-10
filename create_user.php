<?php

// ***** Tem a funcionalidade de conectar ao banco de dados e dar o insert dos dados obtidos no formulário. *****

try {
    $pdo = new PDO('sqlite:database/db.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

$name = $_POST['name'];
$email = $_POST['email'];

if (empty($name) || empty($email)) {
    die("Todos os campos são obrigatórios.");
}

try {
    $sql = "INSERT into users (name, email) values (:name, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Erro ao atualizar o registro: " . $e->getMessage());
}