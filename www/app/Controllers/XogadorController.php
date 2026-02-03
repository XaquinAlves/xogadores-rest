<?php

declare(strict_types=1);
namespace Com\Daw2\Controllers;

use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\XogadorModel;

class XogadorController extends BaseController
{
    public function getXogadores()
    {
        if (isset($_GET['page']) && $_GET['page'] < 0) {
            $respuesta = new Respuesta(400, ['La pagina debe ser mayor o igual a 0']);
        } else {
            $model = new XogadorModel();
            $xogadores = $model->getXogadoresByFilters($_GET);
        }
    }

    public function checkFilters(array $filters): array
    {
        $errors = [];

        if (isset($_GET['page']) && $_GET['page'] < 0) {
            $errors['page'] = 'La pagina debe ser mayor o igual a 0';
        }

        if (isset($_GET['order']) && !in_array($_GET['order'], ['asc', 'desc'])) {
            $errors['order'] = 'El orden debe ser asc o desc';
        }

        return $errors;
    }
}