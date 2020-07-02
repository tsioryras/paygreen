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
        return $this->render('home/index.html.twig', ['error' => '']);
    }

    /**
     * @Route("/create/cash" , name="create_cash" , methods={"POST","GET"})
     * @param Request $request
     * @return mixed|object
     */
    public function createCash(Request $request)
    {
        $amount = str_replace(' ', '', $request->get('amount'));
        $amountParts = strpos($amount, ',') != false ? explode(',', $amount) : explode('.', $amount);

        if (sizeof($amountParts) > 2) {
            return $this->render('home/index.html.twig', ['error' => 'only one "," to separate cents']);
        }

        if (sizeof($amountParts) == 2 && (is_numeric($amountParts[0]) || is_numeric($amountParts[1]))) {
            return $this->render('home/index.html.twig', ['error' => 'numeric value is needed']);
        }

        if (sizeof($amountParts) == 2 && is_numeric($amountParts[0]) && is_numeric($amountParts[1]) && strlen($amountParts[1]) > 2) {
            return $this->render('home/index.html.twig', ['error' => 'only 2 digits for cents']);
        }

        if (sizeof($amountParts) == 2 && is_numeric($amountParts[0]) && is_numeric($amountParts[1]) && strlen($amountParts[1]) < 2) {
            $amount = $amountParts[0] . str_pad($amountParts[1], 2, "0", STR_PAD_RIGHT);
        }

        $data = ['content' =>
            ['amount' => intval($amount),
                "orderId" => "12356548",
                "currency" => "EUR",
                "paymentType" => "CB",
                "notified_url" => "",
                "buyer" => [
                    "id" => "123654789",
                    "lastName" => "Pay",
                    "firstName" => "Green",
                    "email" => "contact@paygreen.fr",
                    "country" => "FR",
                    "companyName" => "PayGreen"
                ],
                "metadata" => [
                    "orderId" => "test-123",
                    "display" => "0"
                ],
            ]
        ];
        dd(PaygreenTransactionHelper::createCash($data));
        return PaygreenTransactionHelper::createCash($data);
    }

    /**
     * @Route("/get/details" , name="get_details" , methods={"POST","GET"})
     * @param Request $request
     * @return mixed|object
     */
    public function getDetails(Request $request)
    {
        dd(PaygreenTransactionHelper::getTransactionInfo($request->get('pid')));
        return PaygreenTransactionHelper::getTransactionInfo($request->get('pid'));
    }
}
