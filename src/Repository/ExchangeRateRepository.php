<?php

namespace App\Repository;

use App\Entity\ExchangeRate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ExchangeRate>
 *
 * @method ExchangeRate|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExchangeRate|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExchangeRate[]    findAll()
 * @method ExchangeRate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExchangeRateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExchangeRate::class);
    }

    public function save(ExchangeRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ExchangeRate $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function getAvailableCurrencies(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = "select id, currency from available_currencies";
        $stmt = $conn->prepare($sql);
        $resultset = $stmt->executeQuery();
        
        $available_currencies = $resultset->fetchAllAssociative();
        
        return $available_currencies;
    }
    
    public function getExchangeRateData(): array
    {
        $conn = $this->getEntityManager()->getConnection();
        
        $sql = "select id, base_currency, target_currency, amount, created_at, TRUNCATE(conversion_rate, 2) AS conversion_rate, "
                . "TRUNCATE(conversion_result, 2) AS conversion_result from exchange_rate";
        $stmt = $conn->prepare($sql);
        $resultset = $stmt->executeQuery();
        
        $exchange_rate_data = $resultset->fetchAllAssociative();
        
        return $exchange_rate_data;
    }
}
