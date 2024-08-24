<?php
include('../config/connect.inc.php');

class UsuarioController {
    public function listarUsuarios() {
        global $pdo;
        try {
            $stmt = $pdo->query("SELECT * FROM usuarios");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar usuários: " . $e->getMessage();
        }
    }

    public function listarUsuariosPorTipo($tipo_usuario) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE tipo_usuario = ?");
            $stmt->execute([$tipo_usuario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao listar usuários por tipo: " . $e->getMessage();
        }
    }

    public function criarUsuario($nome, $email, $matricula, $senha, $tipo_usuario) {
        global $pdo;
        try {
            $nome = htmlspecialchars($nome);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                throw new Exception("Email inválido.");
            }
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, matricula, senha, tipo_usuario) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $matricula, password_hash($senha, PASSWORD_DEFAULT), $tipo_usuario]);
        } catch (PDOException $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function editarUsuario($id, $nome, $email, $senha) {
        global $pdo;
        try {
            $nome = htmlspecialchars($nome);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                throw new Exception("Email inválido.");
            }
            $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
            $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $id]);
        } catch (PDOException $e) {
            echo "Erro ao editar usuário: " . $e->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function obterUsuarioPorId($id) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erro ao obter usuário por ID: " . $e->getMessage();
        }
    }

    public function verificarCredenciais($email, $senha) {
        global $pdo;
        try {
            $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
            $stmt->execute([$email]);
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                return $usuario;
            }
            return false;
        } catch (PDOException $e) {
            echo "Erro ao verificar credenciais: " . $e->getMessage();
        }
    }
}
?>
