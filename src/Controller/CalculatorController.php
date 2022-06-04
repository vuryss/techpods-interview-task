<?php

declare(strict_types=1);

namespace App\Controller;

use App\Calculator\Calculator;
use App\Calculator\Errors\SyntaxError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

use function is_string;

#[AsController]
class CalculatorController extends AbstractController
{
    #[Route(
        path: '/calculator',
    )]
    public function view(): Response
    {
        return $this->render('calculator.html.twig');
    }

    #[Route(
        path: '/calculator/evaluate',
        methods: ['POST'],
    )]
    public function evaluate(Request $request, Calculator $calculator): JsonResponse
    {
        $expression = $request->request->get('expression');

        if (!is_string($expression) || $expression === '') {
            return new JsonResponse(['error' => 'Invalid or missing math expression'], 400);
        }

        try {
            $result = $calculator->evaluateExpression($expression);
        } catch (SyntaxError $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }

        return new JsonResponse(['result' => $result]);
    }
}
