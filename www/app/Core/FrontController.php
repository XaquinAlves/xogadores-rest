<?php

namespace Com\Daw2\Core;

use Ahc\Jwt\JWTException;
use Com\Daw2\Libraries\JwtHelper;
use Com\Daw2\Models\PermisosModel;
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
                $user['permisos'] = (new PermisosModel())->getPermisos($user['user_type']);
            } else {
                $user['permisos'] = [];
            }
            Route::add('/xogador', function () {
                
            });
            
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
