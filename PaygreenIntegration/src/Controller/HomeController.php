<?php

namespace App\Controller;

use Paygreen\PaygreenActionHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("/create/cash" , name="create_cash" , methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createCash(Request $request)
    {
        $amount = trim(str_replace(' ', '', $request->request->get('amount')));
        $amount = trim(str_replace('.', ',', $amount));

        $amountFormatOK = false;
        $amountParts = explode(',', $amount);
        if ((sizeof($amountParts) === 2) && is_numeric($amountParts[0]) && is_numeric($amountParts[1]) && (strlen($amountParts[1]) <= 2)) {
            $amount = intval($amountParts[0] . str_pad($amountParts[1], 2, "0", STR_PAD_RIGHT));
            $amountFormatOK = true;
        }

        if (sizeof($amountParts) === 1 && is_numeric($amount)) {
            $amountFormatOK = true;
            $amount = intval($amount . '00');
        }

        if ($amountFormatOK) {
            $data = ['content' =>
                ['amount' => $amount,
                    "orderId" => "12356548",
                    "currency" => "EUR",
                    "paymentType" => "CB",
                    "notified_url" => "",
                    "buyer" => [
                        "id" => rand(100000, 999999),
                        "lastName" => trim($request->request->get('lastName')),
                        "firstName" => trim($request->request->get('firstName')),
                        "email" => trim($request->request->get('email')),
                        "country" => trim($request->request->get('country')),
                        "ipAddress" => $_SERVER['REMOTE_ADDR'],
                        "companyName" => trim($request->request->get('companyName'))
                    ],
                    "metadata" => [
                        "orderId" => "test-123",
                        "display" => "0"
                    ],
                ]
            ];
            return new JsonResponse(['data' => PaygreenActionHelper::actionFunctions('create-cash', $data), 'error' => '']);
        }
        $error = 'Le montant doit-être un nombre (avec au plus 2 chiffres après la virgule)';
        return new JsonResponse(['error' => $error]);
    }

    /**
     * @Route("/get/details" , name="get_details" , methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getDetails(Request $request)
    {
        $pid = trim($request->get('pid'));
        return new JsonResponse(PaygreenActionHelper::actionFunctions('get-datas', ['pid' => $pid]));
    }
}
