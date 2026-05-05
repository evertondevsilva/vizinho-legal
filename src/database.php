<?php
require_once __DIR__ . '/../config.php';

function buscarCategorias($pdo) {
    return $pdo->query("SELECT * FROM categorias ORDER BY nome ASC")->fetchAll(PDO::FETCH_ASSOC);
}

function buscarFerramentaPorId($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM ferramentas WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function listarFerramentasComCategoria($pdo) {
    $sql = "SELECT f.*, c.nome AS nome_categoria 
            FROM ferramentas f 
            LEFT JOIN categorias c ON f.categoria = c.id    
            ORDER BY f.id DESC";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


function salvarFerramenta($pdo, $dados) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Coleta os dados do formulário
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $descricao = $_POST['descricao'] ?? '';

    try {
        if (empty($id)) {
            // Lógica para NOVO CADASTRO
            $sql = "INSERT INTO ferramentas (nome, categoria, descricao) 
                    VALUES (:nome, :categoria, :descricao)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'      => $nome,
                ':categoria' => $categoria,
                ':descricao' => $descricao,
            ]);
        } else {
            // Lógica para ATUALIZAR EXISTENTE
            $sql = "UPDATE ferramentas 
                    SET nome = :nome, categoria = :categoria, descricao = :descricao 
                    WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'      => $nome,
                ':categoria' => $categoria,
                ':descricao' => $descricao,
                ':id'        => $id
            ]);
        }

        // Redireciona com sucesso
        header("Location: ../../index.php?msg=sucesso");
        exit;

        } catch (PDOException $e) {
            // No TCC, é legal mostrar um erro amigável
            die("Erro no banco de dados: " . $e->getMessage());
        }
    }

}

// Usar esse salvamentFerramenta quando o status estiver no banco na ac3
// function salvarFerramenta($pdo, $dados) {
//     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     // 1. Coleta os dados do formulário
//     $id = $_POST['id'] ?? null;
//     $nome = $_POST['nome'] ?? '';
//     $categoria = $_POST['categoria'] ?? '';
//     $descricao = $_POST['descricao'] ?? '';
//     $status = $_POST['status'] ?? 'disponivel';

//     try {
//         if (empty($id)) {
//             // Lógica para NOVO CADASTRO
//             $sql = "INSERT INTO ferramentas (nome, categoria, descricao, status) 
//                     VALUES (:nome, :categoria, :descricao, :status)";
//             $stmt = $pdo->prepare($sql);
//             $stmt->execute([
//                 ':nome'      => $nome,
//                 ':categoria' => $categoria,
//                 ':descricao' => $descricao,
//                 ':status'    => $status
//             ]);
//         } else {
//             // Lógica para ATUALIZAR EXISTENTE
//             $sql = "UPDATE ferramentas 
//                     SET nome = :nome, categoria = :categoria, status = :status, descricao = :descricao 
//                     WHERE id = :id";
//             $stmt = $pdo->prepare($sql);
//             $stmt->execute([
//                 ':nome'      => $nome,
//                 ':categoria' => $categoria,
//                 ':status'    => $status,
//                 ':descricao' => $descricao,
//                 ':id'        => $id
//             ]);
//         }

//         // Redireciona com sucesso
//         header("Location: ../index.php?msg=sucesso");
//         exit;

//         } catch (PDOException $e) {
//             // No TCC, é legal mostrar um erro amigável
//             die("Erro no banco de dados: " . $e->getMessage());
//         }
//     }

// }

function excluirFerramenta($pdo, $id) {
    $sql = "DELETE FROM ferramentas WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute(['id' => $id]);
}

?>