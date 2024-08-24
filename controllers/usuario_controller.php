<?php
include('../config/connect.inc.php');

class UsuarioController { #faz toda a parte de controle de usuario em forma de consultas
    public function listarUsuarios() {
        global $pdo;
        $stmt = $pdo->query("SELECT * FROM usuarios");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criarUsuario($nome, $email, $matricula, $senha) {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, matricula, senha) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $matricula, password_hash($senha, PASSWORD_DEFAULT)]);
    }

    public function editarUsuario($id, $nome, $email, $senha) {
        global $pdo;
        $stmt = $pdo->prepare("UPDATE usuarios SET nome = ?, email = ?, senha = ? WHERE id = ?");
        $stmt->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT), $id]);
    }
}
?>
