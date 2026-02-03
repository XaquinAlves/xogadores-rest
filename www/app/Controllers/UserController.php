<?php

declare(strict_types=1);

namespace Com\Daw2\Controllers;

use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\JwtHelper;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\UserModel;

class UserController extends BaseController
{
    public function login(): void
    {
        if (empty($_POST['email']) || empty($_POST['password'])) {
            $respuesta = new Respuesta(403, ['Datos de acceso incorrecto']);
        } else {
            $model = new UserModel();
            $user = $model->getByEmail($_POST['email']);
            if ($user === false) {
                $respuesta = new Respuesta(403, ['Datos de acceso incorrecto']);
            } elseif (password_verify($_POST['password'], $user['password'])) {
                unset($user['password']);
                $token = JwtHelper::encode($user);
                $respuesta = new Respuesta(200, ['token' => $token]);
            } else {
                $respuesta = new Respuesta(403, ['Datos de acceso incorrecto']);
            }
        }

        $this->view->show('json.view.php', ['respuesta' => $respuesta]);
    }
}
