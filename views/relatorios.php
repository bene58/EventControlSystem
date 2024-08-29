<?php
session_start();

// Verifica se o usuário é um administrador
if (!isset($_SESSION['email_adm'])) {
    header("Location: login_admin.php");
    exit;
}

include('../config/connect.inc.php');

// Query para obter as informações dos usuários e suas inscrições
$stmtInscricoes = $pdo->query('
    SELECT u.nome AS usuario, u.email, e.nome AS evento, c.titulo AS curso
    FROM usuarios u
    JOIN inscricoes i ON u.id = i.usuario_id
    JOIN cursos c ON i.curso_id = c.id
    JOIN eventos e ON c.evento_id = e.id
');
$inscricoes = $stmtInscricoes->fetchAll(PDO::FETCH_ASSOC);

// Query para obter todos os usuários cadastrados
$stmtUsuarios = $pdo->query('SELECT nome, email FROM usuarios');
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Query para obter todos os eventos e cursos disponíveis
$stmtEventos = $pdo->query('
    SELECT e.id AS evento_id, e.nome AS evento, c.id AS curso_id, c.titulo AS curso, c.descricao, c.data, c.horario 
    FROM eventos e
    LEFT JOIN cursos c ON e.id = c.evento_id
');
$eventosCursos = $stmtEventos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatórios</title>
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Relatórios</h1>

        <!-- Seção de Inscrições -->
        <h2>Relatórios de Inscrições</h2>
        <?php if (!empty($inscricoes)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Email</th>
                        <th>Evento</th>
                        <th>Curso</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscricoes as $inscricao): ?>
                        <tr>
                            <td><?= htmlspecialchars($inscricao['usuario']); ?></td>
                            <td><?= htmlspecialchars($inscricao['email']); ?></td>
                            <td><?= htmlspecialchars($inscricao['evento']); ?></td>
                            <td><?= htmlspecialchars($inscricao['curso']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhuma inscrição disponível no momento.</p>
        <?php endif; ?>

        <!-- Seção de Usuários Cadastrados -->
        <h2>Usuários Cadastrados</h2>
        <?php if (!empty($usuarios)): ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr>
                            <td><?= htmlspecialchars($usuario['nome']); ?></td>
                            <td><?= htmlspecialchars($usuario['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nenhum usuário cadastrado no momento.</p>
        <?php endif; ?>

        <!-- Seção de Eventos e Cursos -->
        <h2>Eventos e Cursos Disponíveis</h2>
        <?php if (!empty($eventosCursos)): ?>
            <ul class="list-group">
                <?php 
                $currentEvent = null;
                foreach ($eventosCursos as $eventoCurso): 
                    if ($currentEvent !== $eventoCurso['evento']) {
                        if ($currentEvent !== null) {
                            echo '</ul>'; // Fecha a lista de cursos do evento anterior
                        }
                        $currentEvent = $eventoCurso['evento'];
                        ?>
                        <li class="list-group-item">
                            <h3><?= htmlspecialchars($eventoCurso['evento']); ?></h3>
                            <ul>
                        <?php 
                    }
                    if ($eventoCurso['curso'] !== null) {
                        ?>
                        <li>
                            <strong>Curso:</strong> <?= htmlspecialchars($eventoCurso['curso']); ?> <br>
                            <strong>Descrição:</strong> <?= htmlspecialchars($eventoCurso['descricao']); ?> <br>
                            <strong>Data:</strong> <?= htmlspecialchars($eventoCurso['data']); ?> <br>
                            <strong>Horário:</strong> <?= htmlspecialchars($eventoCurso['horario']); ?>
                        </li>
                        <?php
                    } else {
                        echo '<li>Nenhum curso cadastrado para este evento.</li>';
                    }
                endforeach; 
                echo '</ul>'; // Fecha a última lista de cursos
                ?>
            </ul>
        <?php else: ?>
            <p>Nenhum evento ou curso disponível no momento.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
