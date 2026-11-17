<?php
namespace App\Exceptions;
use Exception;
class ValidationException extends Exception {
    public function __construct(public array $errors) { parent::__construct('Validation failed'); }
}
