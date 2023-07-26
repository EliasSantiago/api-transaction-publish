<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\WalletNotFoundException;
use App\Exceptions\ZeroValueException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected $service;

    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/transaction",
     *     summary="Criar uma nova transação.",
     *     description="Cria uma nova transação para o usuário autenticado na aplicação.",
     *     tags={"Transaction"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"payer", "payee", "value"},
     *             @OA\Property(property="payer", type="integer", example=2),
     *             @OA\Property(property="payee", type="integer", example=1),
     *             @OA\Property(property="value", type="number", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso na criação da transação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Transaction created successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro - Valor igual a zero",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Zero value is not allowed."),
     *             @OA\Property(property="statusCode", type="integer", example=422)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Erro - Wallet não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Wallet not found."),
     *             @OA\Property(property="statusCode", type="integer", example=404)
     *         )
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="Erro - Saldo insuficiente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Insufficient funds."),
     *             @OA\Property(property="statusCode", type="integer", example=402)
     *         )
     *     )
     * )
     */
    public function store(CreateTransaction $request)
    {
        try {
            $this->service->store($request->all());
            return response()->json(['message' => 'Transaction created successfully']);
        } catch (ZeroValueException $exception) {
            return response()->json(['error' => $exception->getMessage(), 'statusCode' => $exception->getCode()], $exception->getCode());
        } catch (WalletNotFoundException $exception) {
            return response()->json(['error' => $exception->getMessage(), 'statusCode' => $exception->getCode()], $exception->getCode());
        } catch (InsufficientBalanceException $exception) {
            return response()->json(['error' => $exception->getMessage(), 'statusCode' => $exception->getCode()], $exception->getCode());
        }
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }
}
