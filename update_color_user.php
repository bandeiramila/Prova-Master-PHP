<?php
try {
    $pdo = new PDO('sqlite:database/db.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

$id = $_POST['id'];
$blue = $_POST['blue'] === "true";
$green = $_POST['green'] === "true";
$yellow = $_POST['yellow'] === "true";
$red = $_POST['red'] === "true";

try {
    $color_ids = [];
    $pdo->beginTransaction();

    $colors = [
        1 => $blue,
        2 => $red,
        3 => $yellow,
        4 => $green,
    ];

    foreach ($colors as $color_id => $status) {
        if ($status) {
            $checkSql = "SELECT COUNT(*) FROM user_colors WHERE user_id = :user_id AND color_id = :color_id";
            $checkStmt = $pdo->prepare($checkSql);
            $checkStmt->bindParam(':user_id', $id, PDO::PARAM_INT);
            $checkStmt->bindParam(':color_id', $color_id, PDO::PARAM_INT);
            $checkStmt->execute();
            $exists = $checkStmt->fetchColumn();

            if (!$exists) {
                $sql = "INSERT INTO user_colors (user_id, color_id) VALUES (:user_id, :color_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindParam(':color_id', $color_id, PDO::PARAM_INT);
                $stmt->execute();
            }
        } else {
            $sql = "DELETE FROM user_colors WHERE user_id = :user_id AND color_id = :color_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':color_id', $color_id, PDO::PARAM_INT);
            $stmt->execute();
        }
    }

    $pdo->commit();
    header("Location: index.php");
    exit();
} catch (PDOException $e) {
    $pdo->rollBack();
    die("Erro ao atualizar o registro: " . $e->getMessage());
}
