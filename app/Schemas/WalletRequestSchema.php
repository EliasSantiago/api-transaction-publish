<?php

namespace App\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="WalletRequest",
 *     @OA\Property(property="name", type="string", example="Minha Carteira"),
 * )
 */
class WalletRequestSchema{}