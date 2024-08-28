<?php
include('../config/connect.inc.php');
session_start();

$nome = 'Admin Nome';
$email = 'admin@example.com';
$senha = 'adminsenha'; // Senha em texto plano para exemplo

// Cria o hash da senha
$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

// Preparar e executar a consulta SQL para inserir o administrador
/*$stmt = $pdo->prepare('INSERT INTO administradores (nome, email, senha) VALUES (:nome, :email, :senha)');
$stmt->execute([
    'nome' => $nome,
    'email' => $email,
    'senha' => $senha_hash
]);

echo 'Administrador criado com sucesso!';*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha_hash = $_POST['senha'];

    // Verificar se o administrador existe no banco de dados
    $stmt = $pdo->prepare('SELECT * FROM administradores WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        // Verificar se a senha fornecida corresponde ao hash armazenado
        if (password_verify($senha_hash, $admin['senha'])) {
            // Autenticação bem-sucedida
            $_SESSION['email_adm'] = $admin['email'];
            $_SESSION['nome'] = $admin['nome'];
            header("Location: principal.php");
            exit();
        } else {
            // Senha incorreta
            $error = 'E-mail ou senha inválidos.';
        }
    } else {
        // Administrador não encontrado
        $error = 'E-mail ou senha inválidos.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administrador</title>
    <link href="https://bootswatch.com/5/slate/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white text-center">
                        <h3>Login Administrador</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        <form action="login_admin.php" method="post">
                            <div class="mb-3">
                                <label for="email" class="form-label">E-Mail:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-danger">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
