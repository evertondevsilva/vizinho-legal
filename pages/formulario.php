<?php

require_once '../config.php';
require_once '../src/database.php';
require_once '../src/actions/salvaFerramenta.php';

$lista_categorias = buscarCategorias($pdo);

$ferramenta = null;
$titulo_pagina = "Nova Ferramenta";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $ferramenta = buscarFerramentaPorId($pdo, $id);

    if ($ferramenta) {
        $titulo_pagina = "Editar Ferramenta";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo_pagina ?> - Vizinho Legal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
</head>
<body class="container">
    <nav>
        <ul><li><strong>Vizinho Legal</strong></li></ul>
        <ul><li><a href="../index.php">Voltar</a></li></ul>
    </nav>

    <main>
        <h1><?= $titulo_pagina ?></h1>
        
        <form action="../src/actions/salvaFerramenta.php" method="POST">
            <input type="hidden" name="id" value="<?= $ferramenta['id'] ?? '' ?>">

            <label for="nome">Nome
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($ferramenta['nome'] ?? '') ?>" required>
            </label>
            
            <label for="categoria">Categoria</label>
            <select id="categoria" name="categoria" required>
                <option value="">Selecione...</option>
                <?php foreach ($lista_categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" 
                        <?= (isset($ferramenta) && $ferramenta['categoria'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- <?php if ($ferramenta): ?>
            <label for="status">Status</label>
            <select id="status" name="status">
                <option value="disponivel" <?= $ferramenta['status'] == 'disponivel' ? 'selected' : '' ?>>Disponível</option>
                <option value="emprestado" <?= $ferramenta['status'] == 'emprestado' ? 'selected' : '' ?>>Emprestado</option>
            </select>
            <?php endif; ?> -->

            <label for="descricao">Descrição
                <textarea id="descricao" name="descricao" required><?= htmlspecialchars($ferramenta['descricao'] ?? '') ?></textarea>
            </label>

            <button type="submit">Salvar</button>
        </form>
    </main>
</body>
</html>