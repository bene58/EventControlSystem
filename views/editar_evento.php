<?php
include('../config/connect.inc.php');
include('../controllers/evento_controller.php');
include('../controllers/curso_controller.php');
session_start();

// Verifica se o usuário é um administrador
if (!isset($_SESSION['email_adm'])) {
    header("Location: login_admin.php");
    exit;
}

$evento_id = $_GET['id'];
$eventoController = new EventoController($pdo);
$cursoController = new CursoController($pdo);
$evento = $eventoController->getEventoPorId($evento_id);
$cursos = $cursoController->getCursosByEvento($evento_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Atualizar evento
    $titulo = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $data = $_POST['data'];
    $eventoController->editarEvento($evento_id, $titulo, $descricao, $data);

    // Atualizar cursos
    if (!empty($_POST['cursos'])) {
        foreach ($_POST['cursos'] as $curso_id => $curso_data) {
            $cursoController->editarCurso($curso_id, $curso_data['titulo'], $curso_data['descricao'], $curso_data['data'], $curso_data['horario']);
        }
    }

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
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
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

            <!-- Seção para editar cursos -->
            <h3>Cursos</h3>
            <?php if (!empty($cursos)): ?>
                <?php foreach ($cursos as $curso): ?>
                    <div class="border p-3 mb-3">
                        <h4>Curso: <?= htmlspecialchars($curso['titulo']); ?></h4>
                        <div class="mb-3">
                            <label for="titulo_<?= $curso['id']; ?>" class="form-label">Título:</label>
                            <input type="text" id="titulo_<?= $curso['id']; ?>" name="cursos[<?= $curso['id']; ?>][titulo]" class="form-control" value="<?= htmlspecialchars($curso['titulo']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="descricao_<?= $curso['id']; ?>" class="form-label">Descrição:</label>
                            <textarea id="descricao_<?= $curso['id']; ?>" name="cursos[<?= $curso['id']; ?>][descricao]" class="form-control" required><?= htmlspecialchars($curso['descricao']); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="data_<?= $curso['id']; ?>" class="form-label">Data:</label>
                            <input type="date" id="data_<?= $curso['id']; ?>" name="cursos[<?= $curso['id']; ?>][data]" class="form-control" value="<?= htmlspecialchars($curso['data']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="horario_<?= $curso['id']; ?>" class="form-label">Horário:</label>
                            <input type="time" id="horario_<?= $curso['id']; ?>" name="cursos[<?= $curso['id']; ?>][horario]" class="form-control" value="<?= htmlspecialchars($curso['horario']); ?>" required>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Nenhum curso associado a este evento.</p>
            <?php endif; ?>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        </form>
    </div>
</body>
</html>
