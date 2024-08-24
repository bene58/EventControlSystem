<?php include('../config/connect.inc.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Editar Usuário</h1>
    <?php
    include('../controllers/usuario_controller.php');
    
    $controller = new UsuarioController();
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    ?>
    <form action="editar_usuario.php?id=<?php echo $id; ?>" method="post">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" value="<?php echo $usuario['nome']; ?>" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo $usuario['email']; ?>" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha">
        
        <button type="submit">Atualizar</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $senha = $_POST['senha'] ? $_POST['senha'] : $usuario['senha'];
        
        $controller->editarUsuario($id, $nome, $email, $senha);
        
        echo 'Usuário atualizado com sucesso!';
    }
    ?>
</body>
</html>
