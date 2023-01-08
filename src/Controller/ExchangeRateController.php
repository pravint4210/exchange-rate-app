<?php

// src/Controller/ExchangeRateController
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\ExchangeRate;
use Doctrine\Persistence\ManagerRegistry;

class ExchangeRateController extends AbstractController{
    private $client;
    protected $eApiKey;
    private $doctrine;
    
    public function __construct($eApiKey, HttpClientInterface $client, ManagerRegistry $doctrine){
        $this->client = $client;
        $this->eApiKey = $eApiKey;
        $this->doctrine = $doctrine;
    }
    
    public function fetchRates(Request $request): Response{
        $post_values = $request->request->all();
        $statusCode = $this->saveRates($post_values);
        return $this->render('rates/rates.html.twig', [
            'satusCode' => $statusCode,
        ]);
    }
    
    
    public function saveRates($post_values): string
    {
        $base_code = $post_values['from_currency'];
        $target_code = $post_values['target_currency'];
        $amount = $post_values['amount'];
        
        $response = $this->client->request(
            'GET',
            'https://v6.exchangerate-api.com/v6/'.$this->eApiKey.'/pair/'.$base_code.'/'.$target_code.'/'.$amount ? : 1
        );
        $statusCode = $response->getStatusCode();
        $data = $response->toArray();
        if($statusCode == 200 && $data['result'] == 'success'){
            $entityManager = $this->doctrine->getManager();
            $exchange_rate = new ExchangeRate();
            $exchange_rate->setBaseCurrency($base_code);
            $exchange_rate->setTargetCurrency($target_code);
            $exchange_rate->setAmount($amount);
            $exchange_rate->setConversionRate($data['conversion_rate']);
            $exchange_rate->setConversionResult($data['conversion_result']);
            $exchange_rate->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Asia/Kolkata')));
            $entityManager->persist($exchange_rate);
            $entityManager->flush();
        }
        return $statusCode;
    }
}