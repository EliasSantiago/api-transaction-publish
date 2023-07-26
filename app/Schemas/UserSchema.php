<?php
namespace App\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     @OA\Property(property="id", type="integer", example="1"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", format="email", example="john@example.com"),
 *     @OA\Property(property="cpf", type="string", example="123.456.789-10"),
 *     @OA\Property(property="cnpj", type="string", example="12.345.678/0001-90")
 * )
 */
class UserSchema{}