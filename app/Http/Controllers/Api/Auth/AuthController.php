<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    protected $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registrar um novo usuário.",
     *     description="Registra um novo usuário na aplicação.",
     *     tags={"Register"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-10"),
     *             @OA\Property(property="cnpj", type="string", example="12.345.678/0001-90")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Registro bem-sucedido",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Conta criada com sucesso!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Erro de validação dos campos."),
     *             @OA\Property(property="errors", type="object", example={"email": {"O email é obrigatório."}})
     *         )
     *     )
     * )
     */
    public function register(RegisterUserRequest $request)
    {
        $cpf = $request->has('cpf');
        $cnpj = $request->has('cnpj');

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'cpf'       => $cpf ? $request->cpf : null,
            'cnpj'      => $cnpj ? $request->cnpj : null,
            'password'  => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'Conta criada com sucesso!']);
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Realizar login do usuário.",
     *     description="Realiza o login do usuário na aplicação.",
     *     tags={"Login"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="secret"),
     *             @OA\Property(property="logout_others_devices", type="boolean", example=true),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login bem-sucedido",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."),
     *             @OA\Property(property="user", ref="#/components/schemas/User"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas.")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if ($request->has('logout_others_devices')) $user->tokens()->delete();

        return response()->json([
            'token' => $user->createToken("api_transaction")->plainTextToken,
            'user'  => $user,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Obter os dados do usuário autenticado.",
     *     description="Obtém os dados do usuário autenticado na aplicação.",
     *     tags={"Me"},
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Sucesso na obtenção dos dados do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example="1"),
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="cpf", type="string", example="123.456.789-10"),
     *             @OA\Property(property="cnpj", type="string", example="12.345.678/0001-90")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autorizado - Token inválido ou expirado.",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Não autorizado.")
     *         )
     *     )
     * )
     */
    public function me()
    {
        $user = auth()->user();
        return $this->service->getUserData($user->id);
    }
}
