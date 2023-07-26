<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWallet;
use App\Repositories\WalletRepository;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    protected $service;

    public function __construct(WalletService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/wallet",
     *     summary="Criar uma nova wallet.",
     *     description="Cria uma nova wallet para o usuário autenticado na aplicação.",
     *     tags={"Wallet"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/WalletRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sucesso na criação da wallet",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - Token inválido ou expirado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Não autorizado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de validação dos campos."),
     *             @OA\Property(property="errors", type="object", example={"name": {"O nome da wallet é obrigatório."}})
     *         )
     *     )
     * )
     */
    public function store(StoreWallet $request)
    {
        $data = $request->validated();
        $this->service->store($data);
        return response()->json(['message' => 'success'], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/wallet/{id}",
     *     summary="Obter os detalhes de uma wallet.",
     *     description="Obtém os detalhes de uma wallet específica do usuário autenticado na aplicação.",
     *     tags={"Wallet"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da wallet",
     *         required=true,
     *         @OA\Schema(type="integer", format="int64", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso na obtenção dos detalhes da wallet",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Minha Carteira"),
     *             @OA\Property(property="description", type="string", example="Descrição da carteira."),
     *             @OA\Property(property="created_at", type="string", format="date-time"),
     *             @OA\Property(property="updated_at", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - Token inválido ou expirado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Não autorizado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Wallet não encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Wallet não encontrada.")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        return response()->json($this->service->show($id), 200);
    }

    public function update(Request $request, $id)
    {
        //
    }
}
