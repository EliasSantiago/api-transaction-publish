<?php

if (!function_exists('convertArrayToObject')) {
  function convertArrayToObject(array $items)
  {
    $items = array_map(function ($item) {
      $stdClass = new \stdClass;
      foreach ($item as $key => $value) {
        $stdClass->{$key} = $value;
      }
      return $stdClass;
    }, $items);
    return $items;
  }
}
