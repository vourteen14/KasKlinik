<?php

function convertCurrency($number) {
  $units = [
    1000000000000 => 'triliun',
    1000000000 => 'miliar',
    1000000 => 'juta',
    1000 => 'ribu'
  ];

  foreach ($units as $value => $unit) {
    if ($number >= $value) {
      $convertedValue = $number / $value;
      if ($convertedValue == floor($convertedValue)) {
        return number_format($convertedValue, 0, ',', '.') . ' ' . $unit;
      } else {
        return number_format($convertedValue, 2, ',', '.') . ' ' . $unit;
      }
    }
  }
  return number_format($number, 0, ',', '.');
}
