<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

include('../config/connect.inc.php');

// Obter informações do usuário
$id_usuario = $_SESSION['usuario_id'];
$query = $pdo->prepare("SELECT nome, email, matricula FROM usuarios WHERE id = :id");
$query->execute(['id' => $id_usuario]);
$usuario = $query->fetch(PDO::FETCH_ASSOC);

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    
    // Atualiza as informações do usuário, exceto a matrícula
    $update = $pdo->prepare("UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id");
    $update->execute(['nome' => $nome, 'email' => $email, 'id' => $id_usuario]);

    // Atualiza a sessão
    $_SESSION['nome'] = $nome;
    $_SESSION['email'] = $email;

    $_SESSION['mensagem_sucesso'] = "Dados alterados com sucesso!";

    header("Location: editar_perfil.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Perfil</h1>
        
        <?php if (isset($_SESSION['mensagem_sucesso'])): ?>
            <div class="alert alert-success" role="alert">
                <?= $_SESSION['mensagem_sucesso']; ?>
            </div>
            <?php unset($_SESSION['mensagem_sucesso']); // Remove a mensagem da sessão ?>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?= htmlspecialchars($usuario['nome']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="matricula" class="form-label">Matrícula</label>
                <input type="text" class="form-control" id="matricula" value="<?= htmlspecialchars($usuario['matricula']); ?>" disabled>
                <small class="form-text text-muted">A matrícula não pode ser alterada.</small>
            </div>
            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="principal.php" class="btn btn-secondary">Voltar</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
