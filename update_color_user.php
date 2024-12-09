<?php
try {
    $pdo = new PDO('sqlite:database/db.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Debug: Verificar todos os valores recebidos
echo "<pre>";
print_r($_POST);
echo "</pre>";


$id = $_POST['id'];
$blue = $_POST['blue'] === "true";
$green = $_POST['green'] === "true";
$yellow = $_POST['yellow'] === "true";
$red = $_POST['red'] === "true";

echo "ID: $id<br>";
echo "Blue: " . ($blue ? "true" : "false") . "<br>";
echo "Green: " . ($green ? "true" : "false") . "<br>";
echo "Yellow: " . ($yellow ? "true" : "false") . "<br>";
echo "Red: " . ($red ? "true" : "false") . "<br>";
?>