<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
/**
 * @OA\Get(
 *     path="/api/health-check",
 *     summary="Verificar o status da API.",
 *     description="Verifica se a API está funcionando corretamente.",
 *     tags={"Health Check"},
 *     @OA\Response(
 *         response=200,
 *         description="Resposta de sucesso",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="API está funcionando corretamente."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Erro interno do servidor",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Houve um problema no servidor."),
 *         )
 *     )
 * )
 */

class HealthCheckController extends Controller
{
    public function healthCheck()
    {
        return response()->json(['status' => 'ok']);
    }
}
