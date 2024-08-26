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
$eventoController->excluirEvento($evento_id);

header("Location: principal.php");
exit;
?>
