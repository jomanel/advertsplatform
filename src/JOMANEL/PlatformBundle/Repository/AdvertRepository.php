<?php

namespace JOMANEL\PlatformBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AdvertRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */

class AdvertRepository extends \Doctrine\ORM\EntityRepository{

	public function findAdvertById($id){

		$qb = $this->createQueryBuilder('a')
				   ->Where('a.id = '.$id)

		;

	    // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;

	}//fnc

	public function  getAllAdvertsWithPaginator($page, $nbPerPage, $locale){//used in controller

		$qb = $this->createQueryBuilder('a')
					//->select('a.title_fr', 'a.id')
		           ->orderBy('a.date', 'DESC')
		           ->setFirstResult(($page-1) * $nbPerPage)// On définit l'annonce à partir de laquelle commencer la liste
		           ->setMaxResults($nbPerPage) // Ainsi que le nombre d'annonce à afficher sur une page
		;

	    // Enfin, on retourne l'objet Paginator correspondant à la requête construite(n'oubliez pas son use)
	    //return new Paginator($qb, true);

	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;

	}//fnc

	
	public function  getAllAdverts(){//used in controller (*)

		$qb = $this->createQueryBuilder('a');

	    // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;

	}//fnc
	
	public function  getAdvertsWhichHaveApplications(){//used in controller (*)

		$qb = $this->createQueryBuilder('a')
				   //->select('a.id')
		           ->innerJoin('a.applications', 'app')
		           //->select('a.id')
		;

	    // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;

	}//fnc

	public function  getAdvertsWhichAreOld(array $listIds, $days){//used in controller (**)

		$today   = new \DateTime();
		$oldDate = $today->modify('-'.$days.' '.'day');
		

		$qb = $this->createQueryBuilder('a');
		
		$qb->Where($qb->expr()->in('a.id', $listIds))
		   ->andWhere('a.date <= :oldDate')
		   ->setParameter('oldDate', $oldDate)	   
		;
				   
		return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;
	}//fnc

	public function  getAdvertsByIds(array $listIds){//used in controller (***)
	  
	    $qb = $this->createQueryBuilder('a');
	    
	    $qb->Where($qb->expr()->in('a.id', $listIds))
	    ;
	        
	    return $qb
	      ->getQuery()
	      ->getResult()
	    ;
	}


	///////////////////////////////////////// Menu
	public function  getXLastAdvertsTitles($limit, $locale){

		$columnTitle = "title_".$locale;

		$qb = $this->createQueryBuilder('a')
				   ->select('a.'.$columnTitle)
			       ->orderBy('a.id', 'DESC')
	    		   ->setMaxResults($limit)
	    ;


		// Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;

	}//fnc

	public function  getXLastAdvertsIds($limit){

		$qb = $this->createQueryBuilder('a')
				   ->select('a.id')
			       ->orderBy('a.id', 'DESC')
	    		   ->setMaxResults($limit)
	    ;


		// Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getArrayResult()
	    ;

	}//fnc
	/////////////////////////////////////////

	public function  getAdvertsOfOneCategory($categoryName){//used in test

		$qb = $this->createQueryBuilder('a');


		$qb
	      ->innerJoin('a.categories', 'c', 'WITH', 'c.name = :nameCategory')
	      ->addSelect('c')
	      ->setParameter('nameCategory', $categoryName)
	    ;


		// Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getResult()
	    ;

	}//fnc

	public function getAdvertWithCategories(array $categoryNames){//used in test

		// Avec le QueryBuilder
		$qb = $this->createQueryBuilder('a');

	    // On fait une jointure avec l'entité Category avec pour alias « c »
	    $qb
	      ->innerJoin('a.categories', 'c')
	      ->addSelect('c')
	    ;

	    // Puis on filtre sur le nom des catégories à l'aide d'un IN
	    $qb->where($qb->expr()->in('c.name', $categoryNames));
	    // La syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine

	    // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getResult()
	    ;

	}//fnc


}//class
