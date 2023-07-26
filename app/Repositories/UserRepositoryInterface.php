<?php

namespace App\Repositories;

interface UserRepositoryInterface
{
  public function getById(int $id): object | null;
  public function getUserData(int $id): object | null;
}