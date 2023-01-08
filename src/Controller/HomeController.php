<?php

// src/Controller/HomeController
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ExchangeRateRepository;

class HomeController extends AbstractController{
    
    public function home(ExchangeRateRepository $repo): Response
    {
        $available_currencies = $repo->getAvailableCurrencies();
        return $this->render('home/home.html.twig', [
            'availableCurrencies' => $available_currencies,
        ]);
    }
    
    public function getData(ExchangeRateRepository $repo){
        $exchangeRatesData = $repo->getExchangeRateData();
        foreach($exchangeRatesData as &$data){
            $data['created_at'] = date("d M, Y g:i a", strtotime($data['created_at']));
        }
        return $this->render('rates/rates.html.twig', [
            'satusCode' => 200,
            'exchangeRatesData' => $exchangeRatesData
        ]);
    }
}