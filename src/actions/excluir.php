<?php
require_once __DIR__ . '/../../config.php';
require_once __DIR__ . '/../../src/database.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

if ($id) {
    excluirFerramenta($pdo, $id);
}

header("Location: ../../index.php?msg=excluido");
exit;