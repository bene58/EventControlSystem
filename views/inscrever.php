<?php
session_start();
include('../config/connect.inc.php');
include('../controllers/curso_controller.php');

$curso_id = $_GET['curso_id'] ?? null;
$usuario_id = $_SESSION['usuario_id'] ?? null;
$evento_id = $_GET['evento_id'] ?? null;

$cursoController = new CursoController($pdo);

if ($curso_id && $usuario_id) {
    $resultado = $cursoController->inscreverParticipante($curso_id, $usuario_id);
    header('Location: cursos.php?evento_id=' . $evento_id . '&mensagem=' . urlencode($resultado));
    exit;
}

// Se não houver curso_id ou usuario_id, redireciona de volta
header('Location: cursos.php?evento_id=' . $evento_id);
exit;
