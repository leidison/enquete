<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EnqueteRepository extends EntityRepository
{

    public function paginado($filtros, $pagina, $porPagina)
    {
        $queryBuilder = $this->createQueryBuilder('E')
            ->select('E', 'P', 'R')
            ->leftJoin('E.perguntas', 'P')
            ->leftJoin('P.respostas', 'R');



        $todosFiltros = $this->filtroLista($queryBuilder);
        foreach ($filtros as $nome => $valor) {
            $todosFiltros[$nome]($valor);
        }

        $primeiro = $pagina * $porPagina - $porPagina;
        $queryBuilder->setFirstResult($primeiro)
            ->setMaxResults($porPagina);

        $paginador = new Paginator($queryBuilder->getQuery());

        return $paginador->getQuery()->getArrayResult();
    }

    /**
     * Retorna um array de funções anonimas, com todos os filtros
     * @param $queryBuilder
     * @return array
     */
    public function filtroLista($queryBuilder)
    {
        return array(
            'usuario' => function ($valor) use ($queryBuilder) {
                $queryBuilder->where('E.user = :usuario')
                    ->setParameter('usuario', $valor);
            }
            // colocar futuros filtros abaixo
        );
    }
}