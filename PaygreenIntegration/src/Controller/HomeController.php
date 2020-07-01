<?php

namespace App\Controller;

use PaygreenTransactionHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/create/cash" , name="create_cash" , methods={"POST","GET"})
     * @param Request $request
     * @return mixed|object
     */
    public function createCash(Request $request)
    {
        $amount = $request->get('amount');

        $amountparts = strpos($amount, ',') != false ? explode(',', $amount) : explode('.', $amount);
        if (strlen($amountparts[1]) > 2) {
            return $this->render('home/index.html.twig', ['error' => 'only 2 digit for cents']);
        }
        $data = [
            'amount' =>
        ];
        dd(PaygreenTransactionHelper::createCash());
        return PaygreenTransactionHelper::createCash($request->get('amount'));
    }

    /**
     * @Route("/get/details" , name="get_details" , methods={"POST"})
     * @param Request $request
     * @return mixed|object
     */
    public function getDetails(Request $request)
    {
        return PaygreenTransactionHelper::getTransactionInfo($request->get('pid'));
    }
}
