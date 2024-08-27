<?php
include('../config/connect.inc.php');
include('../controllers/evento_controller.php');
session_start();

// Verifica se o usuário é um administrador
if (!isset($_SESSION['email_adm'])) {
    header("Location: login_admin.php");
    exit;
}

$eventoController = new EventoController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $titulo = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];

    // Insere o novo evento
    $stmt = $pdo->prepare('INSERT INTO eventos (nome, descricao, data) VALUES (:nome, :descricao, :data)');
    $stmt->execute(['nome' => $titulo, 'descricao' => $descricao, 'data' => $data]);

    // Redireciona para a página principal após a criação do evento
    header("Location: principal.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Evento</title>
    <link href="https://bootswatch.com/5/quartz/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Criar Novo Evento</h1>
        <form action="criar_evento.php" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea id="descricao" name="descricao" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="data" class="form-label">Data:</label>
                <input type="date" id="data" name="data" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Criar Evento</button>
        </form>
    </div>
</body>
</html>
