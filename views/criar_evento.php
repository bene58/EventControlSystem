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

$eventoController = new EventoController($pdo);
$cursoController = new CursoController($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recebe os dados do formulário
    $tituloEvento = $_POST['nome'];
    $descricaoEvento = $_POST['descricao'];
    $dataEvento = $_POST['data'];
    
    // Insere o novo evento
    $stmt = $pdo->prepare('INSERT INTO eventos (nome, descricao, data) VALUES (:nome, :descricao, :data)');
    $stmt->execute(['nome' => $tituloEvento, 'descricao' => $descricaoEvento, 'data' => $dataEvento]);

    // Obtém o ID do evento recém-criado
    $evento_id = $pdo->lastInsertId();

    // Adiciona cursos, se fornecidos
    $cursoTitulos = $_POST['curso_titulo'];
    $cursoDescricoes = $_POST['curso_descricao'];
    $cursoDatas = $_POST['curso_data'];
    $cursoHorarios = $_POST['curso_horario'];

    foreach ($cursoTitulos as $index => $tituloCurso) {
        if (!empty($tituloCurso)) {
            $descricaoCurso = $cursoDescricoes[$index];
            $dataCurso = $cursoDatas[$index];
            $horarioCurso = $cursoHorarios[$index];
            
            $stmt = $pdo->prepare('INSERT INTO cursos (titulo, descricao, data, horario, evento_id) VALUES (:titulo, :descricao, :data, :horario, :evento_id)');
            $stmt->execute([
                'titulo' => $tituloCurso,
                'descricao' => $descricaoCurso,
                'data' => $dataCurso,
                'horario' => $horarioCurso,
                'evento_id' => $evento_id
            ]);
        }
    }

    // Redireciona para a página principal após a criação do evento e cursos
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
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Criar Novo Evento</h1>
        <form action="criar_evento.php" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome do Evento:</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descricao" class="form-label">Descrição do Evento:</label>
                <textarea id="descricao" name="descricao" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="data" class="form-label">Data do Evento:</label>
                <input type="date" id="data" name="data" class="form-control" required>
            </div>

            <h2>Cursos</h2>
            <div id="cursos-container">
                <div class="curso mb-3">
                    <div class="mb-3">
                        <label class="form-label">Título do Curso:</label>
                        <input type="text" name="curso_titulo[]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição do Curso:</label>
                        <textarea name="curso_descricao[]" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data do Curso:</label>
                        <input type="date" name="curso_data[]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horário do Curso:</label>
                        <input type="time" name="curso_horario[]" class="form-control" required>
                    </div>
                </div>
            </div>
            <button type="button" id="add-curso" class="btn btn-secondary mb-3">Adicionar Outro Curso</button>
            <button type="submit" class="btn btn-primary">Criar Evento</button>
        </form>

        <script>
            document.getElementById('add-curso').addEventListener('click', function() {
                const container = document.getElementById('cursos-container');
                const newCurso = document.createElement('div');
                newCurso.className = 'curso mb-3';
                newCurso.innerHTML = `
                    <div class="mb-3">
                        <label class="form-label">Título do Curso:</label>
                        <input type="text" name="curso_titulo[]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrição do Curso:</label>
                        <textarea name="curso_descricao[]" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data do Curso:</label>
                        <input type="date" name="curso_data[]" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Horário do Curso:</label>
                        <input type="time" name="curso_horario[]" class="form-control" required>
                    </div>
                `;
                container.appendChild(newCurso);
            });
        </script>
    </div>
</body>
</html>
