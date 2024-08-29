<?php
include('../config/connect.inc.php');

class EventoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Função para obter todos os eventos disponíveis
    public function getEventos() {
        $stmt = $this->pdo->query('SELECT * FROM eventos');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para obter cursos associados a um evento específico
    public function getCursosPorEvento($evento_id) {
        $stmt = $this->pdo->prepare('SELECT * FROM cursos WHERE evento_id = :evento_id');
        $stmt->execute(['evento_id' => $evento_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para inscrever um usuário em um curso
    public function inscreverUsuario($usuario_id, $curso_id) {
        $stmt = $this->pdo->prepare('INSERT INTO inscricoes (usuario_id, curso_id) VALUES (:usuario_id, :curso_id)');
        return $stmt->execute(['usuario_id' => $usuario_id, 'curso_id' => $curso_id]);
    }

    // Função para obter um evento específico
    public function getEventoPorId($id) {
        $stmt = $this->pdo->prepare('SELECT * FROM eventos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para editar um evento
    public function editarEvento($id, $titulo, $descricao, $data) {
        $stmt = $this->pdo->prepare('UPDATE eventos SET nome = :nome, descricao = :descricao, data = :data WHERE id = :id');
        return $stmt->execute(['nome' => $titulo, 'descricao' => $descricao, 'data' => $data, 'id' => $id]);
    }

    public function excluirEvento($evento_id) {
        // Primeiro, exclua os cursos associados ao evento
        // Obtenha os IDs dos cursos associados
        $stmt = $this->pdo->prepare('SELECT id FROM cursos WHERE evento_id = :evento_id');
        $stmt->execute(['evento_id' => $evento_id]);
        $cursos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($cursos as $curso_id) {
            // Exclua inscrições associadas ao curso
            $stmt = $this->pdo->prepare('DELETE FROM inscricoes WHERE curso_id = :curso_id');
            $stmt->execute(['curso_id' => $curso_id]);

            // Exclua o curso
            $stmt = $this->pdo->prepare('DELETE FROM cursos WHERE id = :curso_id');
            $stmt->execute(['curso_id' => $curso_id]);
        }

        // Agora, exclua o evento
        $stmt = $this->pdo->prepare('DELETE FROM eventos WHERE id = :evento_id');
        $stmt->execute(['evento_id' => $evento_id]);
    }
}
