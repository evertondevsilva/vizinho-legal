<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $dados = [
        'nome'      => $_POST['nome'],
        'categoria' => $_POST['categoria'],
        'descricao' => $_POST['descricao'],
        // 'status'    => $_POST['status'] ?? 'disponivel'
    ];

    if (empty($id)) {
        salvarFerramenta($pdo, $dados);
    } else {
        salvarFerramenta($pdo, $id, $dados);
    }

    header("Location: ../../index.php?msg=sucesso");
    exit;
}