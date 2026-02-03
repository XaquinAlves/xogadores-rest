<?php

declare(strict_types=1);
namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class UserModel extends BaseDbModel
{
    public function getByEmail(string $email): array|false
    {
        $sql = "SELECT email, name, user_type, password FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

}
