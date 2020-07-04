<?php

namespace App\Controller;

use Paygreen\PaygreenTransactionHelper;
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
        $amount = str_replace(' ', '', $request->request->get('amount'));
        $amount = str_replace('.', ',', $amount);

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
                        "lastName" => $request->request->get('lastName'),
                        "firstName" => $request->request->get('firstName'),
                        "email" => $request->request->get('email'),
                        "country" => $request->request->get('country'),
                        "ipAddress" => $_SERVER['REMOTE_ADDR'],
                        "companyName" => $request->request->get('companyName')
                    ],
                    "metadata" => [
                        "orderId" => "test-123",
                        "display" => "0"
                    ],
                ]
            ];
            return new JsonResponse(['data' => PaygreenTransactionHelper::transactionFunctions('create-cash', $data), 'error' => '']);
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

        return new JsonResponse(PaygreenTransactionHelper::transactionFunctions('get-datas', ['pid' => $request->get('pid')]));
    }
}
