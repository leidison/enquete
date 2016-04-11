<?php
namespace AppBundle\Repository;

use AppBundle\Entity\Enquete;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class EnqueteRepository extends EntityRepository
{

    public function paginado($filtros, $pagina, $porPagina)
    {
        $queryBuilder = $this->createQueryBuilder('E')
            ->select('E', 'P', 'R')
            ->leftJoin('E.perguntas', 'P')
            ->leftJoin('P.respostas', 'R')
            ->addOrderBy('E.data', 'DESC');

        $todosFiltros = $this->filtroLista($queryBuilder);
        foreach ($filtros as $nome => $valor) {
            $todosFiltros[$nome]($valor);
        }

        $primeiro = ($pagina - 1) * $porPagina;
        $queryBuilder->setFirstResult($primeiro)
            ->setMaxResults($porPagina);

        $paginador = new Paginator($queryBuilder);

        // Mudar para uma forma de paginar que seja mais correta
        // que essa e que leve em consideração a query feita acima
        // Abaixo removo os dados de usuário.
        return array_map(function ($enquete) {
            $enquete->setUser(null);
            return $enquete;
        }, $paginador->getIterator()->getArrayCopy());
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

    /**
     * Responde a enquete
     *
     * @param Enquete $enquete
     * @param $avaliacoes
     * @throws \Exception
     */
    public function gerarAvaliacao(Enquete $enquete, $avaliacoes)
    {
        $em = $this->getEntityManager();
        $em->beginTransaction();
        try {
            $qEn = $em->createQueryBuilder()
                ->update('\AppBundle\Entity\Enquete', 'E')
                ->set('E.ajudas', 'E.ajudas +1')
                ->where('E.id = :id')
                ->setParameter('id', $enquete->getId())
                ->getQuery();
            $qEn->execute();

            foreach ($avaliacoes as $avaliacao) {
                $em->createQueryBuilder()
                    ->update('AppBundle\Entity\Resposta', 'R')
                    ->set('R.quantidade', 'R.quantidade +1')
                    ->where('R.id = :id')
                    ->setParameter('id', $avaliacao['resposta'])
                    ->getQuery()
                    ->execute();
            }
            $em->commit();
        } catch (\Exception $e) {
            $em->rollback();
            throw $e;
        }
    }
}