<?php
    require_once ("../connection.php");
    require_once ("../controller/usersDAO.class.php");

    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=utf-8");

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
    } else {
        $id = '';
    }

    if (isset($_GET['name'])) {
        $name = $_GET['name'];
    } else {
        $name = '';
    }

    if (isset($_GET['email'])) {
        $email = $_GET['email'];
    } else {
        $email = '';
    }

    $dao = new UsersDAO_class();

    $lista = $dao -> readUser();

    $json = json_encode($lista);
    echo $json;
