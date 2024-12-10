<?php
try {
    $pdo = new PDO('sqlite:database/db.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

$id = $_POST['id'];
$name = $_POST['name'];
$email = $_POST['email'];

if (empty($id) || empty($name) || empty($email)) {
    die("Todos os campos são obrigatórios.");
}

try {
    $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Erro ao atualizar o registro: " . $e->getMessage());
}