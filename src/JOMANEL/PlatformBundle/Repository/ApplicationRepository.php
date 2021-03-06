<?php

namespace JOMANEL\PlatformBundle\Repository;

/**
 * ApplicationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApplicationRepository extends \Doctrine\ORM\EntityRepository{

	

	// créer une méthode dans l'ApplicationRepository pour récupérer les X dernières candidatures avec leur annonce associée
	public function getApplicationsWithAdvert($limit){//used in controller

		//************************************** Avec le QueryBuilder **************************************//
		$qb = $this->createQueryBuilder('ap');

		/*//==== les X dernières application
	    $qb
	      //->innerJoin('ap.advert', 'a')
	      //->addSelect('a')
	      ->orderBy('ap.id', 'DESC')
	      ->setMaxResults( $limit );
	    ;
	    //====*/


	    //==== ou bien :
	    // On fait une jointure avec l'entité Advert avec pour alias « adv »
	    $qb
	      ->innerJoin('ap.advert', 'adv')
	      ->addSelect('adv')
	      ->orderBy('ap.id', 'DESC')
	    ;

	    // Puis on ne retourne que $limit résultats
	    $qb->setMaxResults($limit);
	    //====


	    // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getResult()
	    ;
	    
		   		  

	}//fnc



	public function getAdvertsWichHaveApplications(){//used in controller

		//************************************** Avec le QueryBuilder **************************************//
		$qb = $this->createQueryBuilder('ap');

		
	    //==== ou bien :
	    // On fait une jointure avec l'entité Advert avec pour alias « adv »
	    $qb
	      ->innerJoin('ap.advert', 'adv')
	      ->addSelect('adv')
	    ;

	    // Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getResult()
	    ;

	}//fnc





	public function getApplicationsOfanAvert($advertTitle){//OneToMany bidi//used in test

		$qb = $this->createQueryBuilder('app');

		//jointure
		$qb
			->innerJoin('app.advert', 'a', 'WITH', 'a.title = :titleAdvert')
			->addSelect('app')
			->setParameter('titleAdvert', $advertTitle)
		;

		// Enfin, on retourne le résultat
	    return $qb
	      ->getQuery()
	      ->getResult()
	    ;

	}//fnc

  /**
   * @param string   $ip
   * @param integer  $seconds
   * @return bool    True si au moins une candidature créée il y a moins de $seconds secondes a été trouvée. False sinon.
   */
  public function isFlood($ip, $seconds)
  {
    return (bool) $this->createQueryBuilder('a')
      ->select('COUNT(a)')
      ->where('a.date >= :date')
      ->setParameter('date', new \Datetime($seconds.' seconds ago'))
      // Nous n'avons pas cet attribut, je laisse en commentaire, mais voici comment pourrait être la condition :
      ->andWhere('a.ip = :ip')->setParameter('ip', $ip)
      ->getQuery()
      ->getSingleScalarResult()
    ;
  }

}//class
