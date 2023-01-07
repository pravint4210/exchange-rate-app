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
}