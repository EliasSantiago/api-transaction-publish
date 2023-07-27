<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
  public function getById(int $userId): object | null;
  public function getUserData(int $userId): object | null;
}