<?php include('../config/connect.inc.php'); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h3>Cadastro</h3>
                    </div>
                    <div class="card-body">
                        <form action="cadastrar_usuario.php" method="post">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <input type="text" id="nome" name="nome" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">E-Mail:</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="matricula" class="form-label">Matrícula:</label>
                                <input type="text" id="matricula" name="matricula" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha:</label>
                                <input type="password" id="senha" name="senha" class="form-control" required>
                            </div>
                            <!-- Seletor de Tipo de Usuário -->
                            <div class="mb-3">
                                <label class="form-label">Tipo de Usuário:</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_usuario" id="organizador" value="organizador" required>
                                    <label class="form-check-label" for="organizador">
                                        Organizador
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="tipo_usuario" id="participante" value="participante" required>
                                    <label class="form-check-label" for="participante">
                                        Participante
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Cadastrar</button>
                            </div>
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            include('../controllers/usuario_controller.php');

                            $nome = $_POST['nome'];
                            $email = $_POST['email'];
                            $matricula = $_POST['matricula'];
                            $senha = $_POST['senha'];
                            $tipo_usuario = $_POST['tipo_usuario'];

                            $controller = new UsuarioController();
                            $controller->criarUsuario($nome, $email, $matricula, $senha, $tipo_usuario);

                            echo '<div class="alert alert-success mt-3" role="alert">Usuário cadastrado com sucesso!</div>';
                            echo '<div class="d-grid mt-3">';
                            echo '<a href="login.php" class="btn btn-secondary">Voltar para Login</a>';
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
