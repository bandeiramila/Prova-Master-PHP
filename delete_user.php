<?php

// ***** Tem a funcionalidade de conectar ao banco de dados e deletar o usuário através do ID obtido *****

try {
    $pdo = new PDO('sqlite:database/db.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

$id = $_POST['id'];

if (empty($id)) {
    die("Nenhum usuário foi selecionado!");
}

try {
    //Duas QUERYs são enviadas ao mesmo tempo para deletar tanto o usuário quanto as possíveis cores que possuía atreladas na DB.
    $sql = "DELETE from users WHERE id = :id";
    $sqlcolor = "DELETE from user_colors where user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $stmt = $pdo->prepare($sqlcolor);
    $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    die("Erro ao atualizar o registro: " . $e->getMessage());
}