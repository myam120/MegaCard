<?php
require '../php/db.php';
header("Content-Type: application/json");

$inputJSON = file_get_contents('php://input');
$data = json_decode($inputJSON, true);

if (!$data) {
    $data = $_POST;
}

echo json_encode([
    "recibido_json" => $data,
    "recibido_post" => $_POST
]);
