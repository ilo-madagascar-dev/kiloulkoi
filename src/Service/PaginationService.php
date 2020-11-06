<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

class PaginationService
{

   /**
    * Paginator Helper
    *
    * @param Doctrine\ORM\Query $query Query Object
    * @param integer            $page  Current page (defaults to 1)
    * @param integer            $limit The total number per page (defaults to 5)
    *
    * @return \Doctrine\ORM\Tools\Pagination\Paginator
    */
   public function paginate($query, $page = 1, $limit = 10): Paginator
   {
       $paginator = new Paginator($query);

       $paginator->getQuery()
                   ->setFirstResult($limit * ($page - 1)) // Offset
                   ->setMaxResults($limit); // Limit

       return $paginator;
   }

   /**
   * @param Paginator $paginator
   * @return int
   */
   public function lastPage(Paginator $paginator): int
   {
      return ceil($paginator->count() / $paginator->getQuery()->getMaxResults());
   }
   
   /**
   * @param Paginator $paginator
   * @return int
   */
   public function total(Paginator $paginator): int
   {
      return $paginator->count();
   }
   
}