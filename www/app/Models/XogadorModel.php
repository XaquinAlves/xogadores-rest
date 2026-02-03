<?php

declare(strict_types=1);
namespace Com\Daw2\Models;

use Com\Daw2\Core\BaseDbModel;

class XogadorModel extends BaseDbModel
{
    private const ORDER_BY = ['numero_licencia', 'equipo.nome_equipo', 'xogador.nome', 'estatura', 'data_nacemento'];
    private const PAGE_SIZE = 30;
    public function getXogadoresByFilters(array $filters): array
    {
        $sql = "SELECT numero_licencia, xogador.codigo_equipo, equipo.nome_equipo, xogador.nome , estatura, posicion, 
                nacionalidade, ficha, data_nacemento, temporadas
                FROM xogador LEFT JOIN equipo ON equipo.codigo = xogador.codigo_equipo ";
        $params = [];
        $conditions = [];

        if (!empty($filters['numero_licencia'])) {
            $conditions[] = "numero_licencia = :numero_licencia ";
            $params['numero_licencia'] = $filters['numero_licencia'];
        }

        if (!empty($filters['codigo_equipo'])) {
            $conditions[] = "codigo_equipo = :codigo_equipo ";
            $params['codigo_equipo'] = $filters['codigo_equipo'];
        }

        if (!empty($filters['nome_equipon'])) {
            $conditions[] = "nome_equipo LIKE :nome_equipo ";
            $params['nome_equipo'] = "%{$filters['nome_equipo']}%";
        }

        if (!empty($filters['nome_xogador'])) {
            $conditions[] = "nome_xogador LIKE :nome_xogador ";
            $params['nome_xogador'] = "%{$filters['nome_xogador']}%";
        }

        if (!empty($filters['min_estatura'])) {
            $conditions[] = "estatura >= :min_estatura ";
            $params['min_estatura'] = $filters['min_estatura'];
        }

        if (!empty($filters['max_estatura'])) {
            $conditions[] = "estatura <= :max_estatura ";
            $params['max_estatura'] = $filters['max_estatura'];
        }

        if (!empty($conditions)) {
            $sql .= 'WHERE ' . implode('AND ', $conditions);
        }

        $sql .= ' ORDER BY ';
        if (empty($filters['order'])) {
            $sql .= self::ORDER_BY[0];
        } else {
            $sql .= self::ORDER_BY[$filters['order']];
        }

        if (!empty($filters['sentido'])) {
            $sql .= " {$filters['sentido']} ";
        }

        $sql .= ' LIMIT ';
        if (empty($filters['page'])) {
            $sql .= '0 , ' . self::PAGE_SIZE;
        } else {
            $sql .= ($filters['page'] - 1) * self::PAGE_SIZE . ', ' . self::PAGE_SIZE;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
