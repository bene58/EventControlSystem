<?php

class CursoController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function criarCurso($titulo, $descricao, $data, $horario, $evento_id) {
        $stmt = $this->pdo->prepare("INSERT INTO cursos (titulo, descricao, data, horario, evento_id) VALUES (:titulo, :descricao, :data, :horario, :evento_id)");
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data', $data);
        $stmt->bindParam(':horario', $horario);
        $stmt->bindParam(':evento_id', $evento_id);
        return $stmt->execute();
    }

    // Função para obter todos os cursos de um evento específico
    public function getCursosByEvento($evento_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cursos WHERE evento_id = :evento_id");
        $stmt->bindParam(':evento_id', $evento_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Função para obter um curso específico
    public function getCursoById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cursos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para inscrever um participante em um curso
    public function inscreverParticipante($curso_id, $usuario_id) {
        // Verifica se o usuário já está inscrito no curso
        $stmt = $this->pdo->prepare("SELECT * FROM inscricoes WHERE curso_id = :curso_id AND usuario_id = :usuario_id");
        $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return "Você já está inscrito neste curso.";
        }

        // Insere a inscrição no banco de dados
        $stmt = $this->pdo->prepare("INSERT INTO inscricoes (curso_id, usuario_id) VALUES (:curso_id, :usuario_id)");
        $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "Inscrição realizada com sucesso!";
        } else {
            return "Erro ao realizar a inscrição.";
        }
    }


    public function verificarInscricao($curso_id, $usuario_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM inscricoes WHERE curso_id = :curso_id AND usuario_id = :usuario_id");
        $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }


    public function editarCurso($curso_id, $titulo, $descricao, $data, $horario) {
        $stmt = $this->pdo->prepare("
            UPDATE cursos 
            SET titulo = :titulo, descricao = :descricao, data = :data, horario = :horario 
            WHERE id = :curso_id
        ");
        $stmt->execute([
            'titulo' => $titulo,
            'descricao' => $descricao,
            'data' => $data,
            'horario' => $horario,
            'curso_id' => $curso_id
        ]);
    }
}
?>
