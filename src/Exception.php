<?php
namespace FortyeightDesign\Phmiley;

class Exception extends \Exception
{
  public function errorMessage() {
    return '<strong>' . htmlspecialchars($this->getMessage()) . "</strong><br>" . PHP_EOL;
  }
}