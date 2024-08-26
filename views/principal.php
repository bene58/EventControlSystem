<?php
session_start();

// Verifica se o usuário é um administrador
$is_admin = isset($_SESSION['email_adm']);

include('../config/connect.inc.php');
include('../controllers/evento_controller.php');

// Instanciar o EventoController
$eventoController = new EventoController($pdo);

// Obter todos os eventos
$eventos = $eventoController->getEventos();
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
        <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome'] ?? 'Usuário não identificado'); ?>!</h1>
        <p>Você está na página principal.</p>
        
        <h2>Eventos Disponíveis</h2>
        <?php if (!empty($eventos)): ?>
            <ul class="list-group">
                <?php foreach ($eventos as $evento): ?>
                    <li class="list-group-item">
                        <h3><?= htmlspecialchars($evento['nome']); ?></h3>
                        <p><?= htmlspecialchars($evento['descricao']); ?></p>
                        <a href="cursos.php?evento_id=<?= $evento['id']; ?>" class="btn btn-primary">Ver Cursos</a>
                        
                        <?php if ($is_admin): ?>
                            <a href="editar_evento.php?id=<?= $evento['id']; ?>" class="btn btn-warning">Editar</a>
                            <a href="excluir_evento.php?id=<?= $evento['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este evento?');">Excluir</a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Nenhum evento disponível no momento.</p>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-danger mt-4">Sair</a>
    </div>
</body>
</html>
