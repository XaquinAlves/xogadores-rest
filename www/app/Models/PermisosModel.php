<?php

declare(strict_types=1);
namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class PermisosModel extends BaseDbModel
{
    public function getPermisos(string $user_type): array
    {
        $sql = "SELECT tabla, permisos FROM permisos WHERE user_type = :user_type";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['user_type' => $user_type]);
        $permisos = $stmt->fetchAll();
        $result = [];
        foreach ($permisos as $permiso) {
            $result[$permiso['tabla']] = $permiso['permisos'];
        }
        return $result;
    }
}