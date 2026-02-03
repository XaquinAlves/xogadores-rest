<?php

declare(strict_types=1);
namespace Com\Daw2\Controllers;

use Com\Daw2\Core\BaseController;
use Com\Daw2\Libraries\Respuesta;
use Com\Daw2\Models\XogadorModel;

class XogadorController extends BaseController
{
    public function getXogadores(): void
    {
        $errors = $this->checkFilters($_GET);
        if ($errors === []) {
            try {
                $listaXogadores = (new XogadorModel())->getXogadoresByFilters($_GET);
                $respuesta = new Respuesta(200, $listaXogadores);
            } catch (\PDOException $e) {
                $respuesta = new Respuesta(500, ['Error interno del servidor']);
                throw $e;
            }
        } else {
            $respuesta = new Respuesta(400, $errors);
        }
        $this->view->show('json.view.php', ['respuesta' => $respuesta]);
    }

    private function checkFilters(array $filters): array
    {
        $errors = [];

        if (
            isset($_GET['numero_licencia']) &&
            (!filter_var($_GET['numero_licencia'], FILTER_VALIDATE_INT) || $_GET['numero_licencia'] <= 0)
        ) {
            $errors['numero_licencia'] = 'El numero de licencia debe ser un entero mayor a 0';
        }

        if (isset($_GET['page']) && $_GET['page'] < 0) {
            $errors['page'] = 'La pagina debe ser mayor o igual a 0';
        }

        if (isset($_GET['sentido']) && !in_array($_GET['sentido'], ['asc', 'desc'])) {
            $errors['sentido'] = 'El sentido debe ser asc o desc';
        }

        if (isset($_GET['order']) && ($_GET['order'] < 1 || $_GET['order'] > 5)) {
            $errors['order'] = 'El orden debe ser entre 1 y 5';
        }

        return $errors;
    }

    public function getXogador(int $num_licencia): void
    {
        $xogador = (new XogadorModel())->getXogadorByNumeroLicencia($num_licencia);
        if ($xogador === false) {
            $respuesta = new Respuesta(404, ['Xogador non encontrado']);
        } else {
            $respuesta = new Respuesta(200, $xogador);
        }
        $this->view->show('json.view.php', ['respuesta' => $respuesta]);
    }

    public function deleteXogador(int $num_licencia): void
    {
        $result = (new XogadorModel())->deleteXogador($num_licencia);

        if ($result === false) {
            $respuesta = new Respuesta(404, ['Xogador non encontrado']);
        } else {
            $respuesta = new Respuesta(200, ['Xogador eliminado']);
        }

        $this->view->show('json.view.php', ['respuesta' => $respuesta]);
    }
}
