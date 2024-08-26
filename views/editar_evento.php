<?php
include('../config/connect.inc.php');
include('../controllers/evento_controller.php');
session_start();

// Verifica se o usuário é um administrador
if (!isset($_SESSION['email_adm'])) {
    header("Location: login_admin.php");
    exit;
}

$evento_id = $_GET['id'];
$eventoController = new EventoController($pdo);
$evento = $eventoController->getEventoPorId($evento_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];

    $eventoController->editarEvento($evento_id, $titulo, $descricao, $data);
    header("Location: principal.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Evento</h1>
        <form action="editar_evento.php?id=<?= $evento['id']; ?>" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($evento['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição:</label>
                <textarea id="descricao" name="descricao" class="form-control" required><?= htmlspecialchars($evento['descricao']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="data" class="form-label">Data:</label>
                <input type="date" id="data" name="data" class="form-control" value="<?= htmlspecialchars($evento['data']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
