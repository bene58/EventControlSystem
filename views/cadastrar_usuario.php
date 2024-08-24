<?php include('../config/connect.inc.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Cadastro de Usuário</h1>
    <form action="cadastrar_usuario.php" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="matricula">Matrícula:</label>
        <input type="text" id="matricula" name="matricula" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>
        
        <button type="submit">Cadastrar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        include('../controllers/usuario_controller.php');
        
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $matricula = $_POST['matricula'];
        $senha = $_POST['senha'];
        
        $controller = new UsuarioController();
        $controller->criarUsuario($nome, $email, $matricula, $senha);
        
        echo 'Usuário cadastrado com sucesso!';
    }
    ?>
</body>
</html>