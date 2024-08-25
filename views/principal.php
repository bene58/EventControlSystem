<?php
session_start();

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}
if (isset($_SESSION['nome'])) {
    $nome = $_SESSION['nome'];
} else {
    $nome = 'Usuário não identificado'; // Ou qualquer valor padrão
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Bem-vindo, <?= htmlspecialchars($nome); ?>!</h1>
        <p>Você está na página principal.</p>
        <a href="logout.php" class="btn btn-danger">Sair</a>
    </div>
</body>
</html>
