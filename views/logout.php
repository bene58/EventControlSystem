<?php
session_start();
session_destroy();

// Redireciona para a página de login após logout
header("Location: ../index.html");
exit;
?>
