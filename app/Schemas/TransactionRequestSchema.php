<?php

namespace App\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="TransactionRequest",
 *     required={"payer", "payee", "value"},
 *     @OA\Property(property="payer", type="integer", example=2),
 *     @OA\Property(property="payee", type="integer", example=1),
 *     @OA\Property(property="value", type="number", example=10)
 * )
 */
class TransactionRequestSchema{}