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
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .container {
            flex: 1;
        }
        footer {
            background-color: #343a40;
            color: white;
            padding: 15px 0;
            text-align: center;
        }
        .navbar {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="logo.png" alt="Eventuxos Logo" style="max-width: 150px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if (!$is_admin): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="editar_perfil.php">
                                    <i class="bi bi-person-circle"></i> Perfil
                                </a>
                            </li>
                        <?php endif; ?> 
                        <?php if ($is_admin): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="relatorios.php">Relatórios</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>


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
                        <p><strong>Data:</strong> <?= htmlspecialchars($evento['data']); ?></p> 
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

        <?php if ($is_admin): ?>
            <a href="criar_evento.php" class="btn btn-success mt-4">Criar Novo Evento</a>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-danger mt-4">Sair</a>
    </div>

    <!-- Rodapé -->
    <footer>
        <div class="container">
            <h5>Integrantes</h5>
            <ul class="list-unstyled">
                <li>Aline Luiza Souza | aline.l.souza@ufv.br | 6698</li>
                <li>Luiz Benedito Alves Neto | luiz.b.neto@ufv.br | 7557</li>
                <li>Mariana de Deus Castro | mariana.d.castro@ufv.br | 7583</li>
            </ul>
            <p>&copy; <?= date('Y'); ?> Eventuxos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
