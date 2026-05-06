<?php
require_once __DIR__ . '/src/database.php';

$categorias = buscarCategorias($pdo);

$termo_busca = $_GET['busca'] ?? null;
$filtro_categoria = $_GET['categoria'] ?? null;

$ferramentas = buscarFerramentas($pdo, $termo_busca, $filtro_categoria);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Lista de Ferramentas - Vizinho Legal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@1/css/pico.min.css">
    <style>
        .linha-detalhes { display: none !important; }
        .linha-detalhes.ativa { display: table-row !important; background-color: rgba(255, 255, 255, 0.05); }
        .conteudo-detalhes { padding: 20px; border-left: 4px solid var(--primary); margin: 5px 0; }
        .acoes a { margin: 0 5px; }
    </style>
</head>
<body class="container">
    <nav>
        <ul><li><strong>Vizinho Legal</strong></li></ul>
        <ul>
            <li><a href="index.php" class="outline">Listagem</a></li>
            <li><a href="pages/formulario.php">Cadastrar Nova</a></li>
        </ul>
    </nav>
    <main>
        <form method="GET" class="mb-6 flex flex-wrap gap-2">
            <input type="text" name="busca" placeholder="Pesquisar ferramenta..." 
                class="border p-2 rounded flex-grow" value="<?= $_GET['busca'] ?? '' ?>">
            
            <select name="categoria" class="border p-2 rounded">
                <option value="">Todas as Categorias</option>
                <?php foreach($categorias as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($_GET['categoria']) && $_GET['categoria'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= $cat['nome'] ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrar</button>
            <a href="index.php" class="bg-gray-200 px-4 py-2 rounded">Limpar Filtro</a>
        </form>
        <h1>Lista de Ferramentas</h1>
        
        <?php if(isset($_GET['msg'])): ?>
            <ins>Operação realizada com sucesso!</ins>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Categoria</th>
                    <!-- <th>Status</th> -->
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($ferramentas as $f): ?>
                <tr>
                    <td><?= htmlspecialchars($f['nome']) ?></td>
                    <td><?= htmlspecialchars($f['nome_categoria'] ?: 'Sem Categoria') ?></td>
                    <!-- <td>
                        <?php 
                            $cor = ($f['status'] == 'disponivel') ? 'green' : (($f['status'] == 'manutencao') ? 'orange' : 'grey');
                        ?>
                        <mark style="background-color: <?= $cor ?>; color: white; padding: 2px 8px; border-radius: 4px;">
                            <?= ucfirst($f['status']) ?>
                        </mark>
                    </td> -->
                    <td class="acoes">
                        <a href="#" onclick="alternarDetalhes(<?= $f['id'] ?>); return false;">Descritivo</a> | 
                        <a href="pages/formulario.php?id=<?= $f['id'] ?>">Editar</a> | 
                        <a href="src/actions/excluir.php?id=<?= $f['id'] ?>" style="color:red" onclick="return confirm('Excluir esta ferramenta?')">Excluir</a>
                    </td>
                </tr>
                
                <tr id="detalhes-<?= $f['id'] ?>" class="linha-detalhes">
                    <td colspan="4">
                        <div class="conteudo-detalhes">
                            <strong>Descrição Completa:</strong><br>
                            <p style="margin-top: 10px;">
                                <?= nl2br(htmlspecialchars($f['descricao'] ?: 'Nenhuma descrição informada.')) ?>
                            </p>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                
            </tbody>
            
        </table>
        
        <?php if (empty($ferramentas)): ?>
                    <div class="p-8 text-center bg-gray-50 rounded-lg border-2 border-dashed">
                        <p class="text-gray-500">Nenhuma ferramenta encontrada para esta busca.</p>
                    </div>
                    <?php else: ?>
                <?php endif; ?>
    </main>

    <script>
    function alternarDetalhes(id) {
        const linha = document.getElementById('detalhes-' + id);
        if (linha.classList.contains('ativa')) {
            linha.classList.remove('ativa');
        } else {
            document.querySelectorAll('.linha-detalhes').forEach(l => l.classList.remove('ativa'));
            linha.classList.add('ativa');
        }
    }
    </script>
</body>
</html>