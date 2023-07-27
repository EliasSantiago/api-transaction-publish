<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class AuthorizationService
{
  public function checkAuthorization(): bool
  {
    $httpClient = new Client();

    try {
      $response = $httpClient->get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

      if ($response->getStatusCode() === 200) {
        $responseData = json_decode($response->getBody(), true);
        return isset($responseData['message']) && $responseData['message'] === "Autorizado";
      }
    } catch (\Exception $e) {
      Log::error('Erro na requisição de autorização: ' . $e->getMessage());
      return false;
    }

    return false;
  }
}
