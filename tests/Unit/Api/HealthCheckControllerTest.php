<?php

namespace Tests\Unit\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\HealthCheckController;

class HealthCheckControllerTest extends TestCase
{
  public function testHealthCheck()
  {
    $controller = new HealthCheckController();
    $response = $controller->healthCheck();
    $this->assertEquals(200, $response->getStatusCode());
    $expectedContent = ['status' => 'ok'];
    $this->assertEquals($expectedContent, json_decode($response->getContent(), true));
  }
}
