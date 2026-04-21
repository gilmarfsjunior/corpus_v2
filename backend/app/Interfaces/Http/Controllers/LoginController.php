<?php

namespace App\Interfaces\Http\Controllers;

use App\Infrastructure\Database\Connection;
use App\Shared\Http\Response;

class LoginController
{
    private Connection $connection;

    public function __construct()
    {
        $config = require dirname(__DIR__, 4) . '/config/database.php';
        $this->connection = new Connection($config);
    }

    public function login(): Response
    {
        $data = $this->getRequestData();

        $usuario = trim($data['usuario'] ?? '');
        $senha = trim($data['senha'] ?? '');
        $empresa = trim($data['empresa'] ?? '1');

        if (empty($usuario) || empty($senha)) {
            return Response::json(['message' => 'Usuário e senha são obrigatórios'], 400);
        }

        $sql = "SELECT CodUsuario, NomeUsuario, NivelUsuario, Empresa FROM tusuarios 
                WHERE LOWER(NomeUsuario) = LOWER(:usuario) AND SenhaUsuario = :senha AND Empresa = :empresa AND Ativo = 'S'";
        
        $statement = $this->connection->getPdo()->prepare($sql);
        $statement->execute([
            ':usuario' => $usuario,
            ':senha' => $senha,
            ':empresa' => $empresa,
        ]);

        $user = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            return Response::json(['message' => 'Usuário ou senha inválidos'], 401);
        }

        // For simplicity, return user data. In production, use JWT or proper session
        return Response::json([
            'user' => [
                'id' => $user['CodUsuario'],
                'nome' => $user['NomeUsuario'],
                'nivel' => $user['NivelUsuario'],
                'empresa' => $user['Empresa'],
            ]
        ]);
    }

    private function getRequestData(): array
    {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (is_array($data)) {
            return $data;
        }

        return $_POST;
    }
}