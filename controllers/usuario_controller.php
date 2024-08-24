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

    public function criarUsuario($nome, $email, $matricula, $senha) {
        global $pdo;
        try {
            
            $nome = htmlspecialchars($nome);
            $email = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (!$email) {
                throw new Exception("Email inválido.");
            }
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, matricula, senha) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $email, $matricula, password_hash($senha, PASSWORD_DEFAULT)]);
        } catch (PDOException $e) {
            echo "Erro ao criar usuário: " . $e->getMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function editarUsuario($id, $nome, $email, $senha) {
        global $pdo;
        try {
            // Validação básica
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
}
?>
