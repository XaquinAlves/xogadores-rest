<?php

namespace Com\Daw2\Core;

use Ahc\Jwt\JWTException;
use Com\Daw2\Controllers\UserController;
use Com\Daw2\Controllers\XogadorController;
use Com\Daw2\Libraries\JwtHelper;
use Com\Daw2\Models\PermisosModel;
use Com\Daw2\Models\XogadorModel;
use Com\Daw2\Traits\JwtTool;
use Steampixel\Route;

class FrontController
{
    public static ?array $user = null;
    public static function main(): void
    {

        try {
            if (JwtTool::requestHasToken()) {
                $token = JwtTool::getBearerToken();
                $user = JwtHelper::decode($token);
                self::$user['permisos'] = (new PermisosModel())->getPermisos($user['user_type']);
            }
            Route::add('/login', function () {
                (new UserController())->login();
            }, 'post');
            Route::add('/xogador', function () {
                if (self::$user !== null && str_contains(self::$user['permisos']['xogador'], 'r')) {
                    (new XogadorController())->getXogadores();
                } else {
                    http_response_code(403);
                }
            });
            Route::add('/xogador/(\d*)', function ($num_licencia) {
                if (self::$user !== null && str_contains(self::$user['permisos']['xogador'], 'r')) {
                    (new XogadorController())->getXogador((int)$num_licencia);
                } else {
                    http_response_code(403);
                }
            });
            Route::add('/xogador/(\d*)', function ($num_licencia) {
                if (self::$user !== null && str_contains(self::$user['permisos']['xogador'], 'r')) {
                    (new XogadorController())->deleteXogador((int)$num_licencia);
                } else {
                    http_response_code(403);
                }
            }, 'delete');
            Route::pathNotFound(
                function () {
                    http_response_code(404);
                }
            );
            Route::methodNotAllowed(
                function () {
                    http_response_code(405);
                }
            );
            Route::run();
        } catch (JWTException $e) {
            http_response_code(403);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
