<?php
function carregarEnv($caminho) {
    if (!file_exists($caminho)) return false;
    $linhas = file($caminho, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        if (strpos(trim($linha), '#') === 0) continue;
        $partes = explode('=', $linha, 2);
        if (count($partes) == 2) {
            putenv(trim($partes[0]) . '=' . trim($partes[1]));
        }
    }
    return true;
}

carregarEnv(__DIR__ . '/.env');

$config = [
    'host' => getenv('DB_HOST'),
    'db'   => getenv('DB_NAME'),
    'user' => getenv('DB_USER'),
    'pass' => getenv('DB_PASS')
];

try {
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['db']};charset=utf8", 
        $config['user'], 
        $config['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die("Falha na conexão: Verifique as variáveis de ambiente.");
}