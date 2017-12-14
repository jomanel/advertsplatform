<?php

namespace JOMANEL\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository{

 
	public function sortAlphabeticallyQueryBuilder(){//used in advert type
	    return $this
	      ->createQueryBuilder('c')
	      ->orderBy('c.name', 'ASC')
	    ;
  	}

  	public function getAllCategories($locale){
  		
  		$column = "name_".$locale;

  		$qb = $this->createQueryBuilder('c')
  				   ->orderBy('c.id', 'ASC')
                   ->select('c.'.$column)
        ;

        // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;
  	}

  	public function getAllIdsCategories(){

  		$qb = $this->createQueryBuilder('c')
  				   ->select('c.id')
  				   ->orderBy('c.id', 'ASC')
                   
        ;

        // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;
  	}

  	public function getOneCategory($locale, $id){
  		
  		$column = "name_".$locale;

  		$qb = $this->createQueryBuilder('c')
                   ->select('c.'.$column)
                   ->Where('c.id ='.$id.'')
        ;

        // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;
  	}

  	public function  getAllCategoriesWithPaginator($page, $nbPerPage, $locale){

		$column = "name_".$locale;

		$qb = $this->createQueryBuilder('c')
				   //->select('c.id')
		           ->orderBy('c.id', 'DESC')
		           ->setFirstResult(($page-1) * $nbPerPage)// On définit l'annonce à partir de laquelle commencer la liste
		           ->setMaxResults($nbPerPage) // Ainsi que le nombre d'annonce à afficher sur une page
		;

	    // Enfin, on retourne l'objet Paginator correspondant à la requête construite(n'oubliez pas son use)
	    return new Paginator($qb, true);

	}//fnc

}//fnc
