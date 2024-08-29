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

    public function getCursosByEvento($evento_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cursos WHERE evento_id = :evento_id");
        $stmt->bindParam(':evento_id', $evento_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCursoById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM cursos WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Função para inscrever um participante em um curso
    public function inscreverParticipante($curso_id, $usuario_id) {
        // Primeiro, obter a data e o horário do curso em que o usuário deseja se inscrever
        $stmt = $this->pdo->prepare("SELECT data, horario FROM cursos WHERE id = :curso_id");
        $stmt->bindParam(':curso_id', $curso_id, PDO::PARAM_INT);
        $stmt->execute();
        $cursoAtual = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cursoAtual) {
            return "Curso não encontrado.";
        }

        // Verificar se o usuário já está inscrito em um curso no mesmo dia e horário
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) 
            FROM inscricoes i
            JOIN cursos c ON i.curso_id = c.id
            WHERE i.usuario_id = :usuario_id 
            AND c.data = :data 
            AND c.horario = :horario
        ");
        $stmt->execute([
            'usuario_id' => $usuario_id,
            'data' => $cursoAtual['data'],
            'horario' => $cursoAtual['horario'],
        ]);

        $count = $stmt->fetchColumn();

        if ($count > 0) {
            
            return "Você já está inscrito em um curso no mesmo horário e data.";
        }

        // Caso não esteja inscrito, prosseguir com a inscrição
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
