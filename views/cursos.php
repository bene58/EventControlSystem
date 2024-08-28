<?php
session_start();
include('../config/connect.inc.php');
include('../controllers/curso_controller.php');

$evento_id = $_GET['evento_id'] ?? null;
$cursoController = new CursoController($pdo);

// Verifica se o usuário está logado
$usuario_id = $_SESSION['usuario_id'] ?? null;

if ($evento_id) {
    $cursos = $cursoController->getCursosByEvento($evento_id);
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos Disponíveis</title>
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
    <script>
        function confirmarInscricao(cursoId, eventoId) {
            if (confirm('Confirmar inscrição?')) {
                // Redireciona para o script PHP para inscrever o usuário
                window.location.href = `inscrever.php?curso_id=${cursoId}&evento_id=${eventoId}`;
            }
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h1>Cursos Disponíveis</h1>

        <?php if (!empty($cursos)): ?>
            <ul class="list-group">
                <?php foreach ($cursos as $curso): ?>
                    <li class="list-group-item">
                        <h3><?= htmlspecialchars($curso['titulo']); ?></h3>
                        <p><?= htmlspecialchars($curso['descricao']); ?></p>
                        <p><strong>Data:</strong> <?= htmlspecialchars($curso['data']); ?></p>
                        <p><strong>Horário:</strong> <?= htmlspecialchars($curso['horario']); ?></p>
                        
                        <!-- Botão de Inscrição -->
                        <?php if ($usuario_id): ?>
                            <?php
                                // Verifica se o usuário já está inscrito no curso
                                $inscrito = $cursoController->verificarInscricao($curso['id'], $usuario_id);
                            ?>
                            <?php if ($inscrito): ?>
                                <button class="btn btn-secondary" disabled>Inscrito</button>
                            <?php else: ?>
                                <button class="btn btn-success" onclick="confirmarInscricao(<?= $curso['id']; ?>, <?= $evento_id; ?>)">Inscrever-se</button>
                            <?php endif; ?>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum curso disponível para este evento.</p>
        <?php endif; ?>
        <div>
            <a href="principal.php" class="btn btn-primary mt-4">Voltar</a>
        </div>
    </div>
</body>
</html>
