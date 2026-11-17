<?php
namespace App\Core;

use App\Exceptions\ValidationException;

class Validator {
    private array $errors = [];

    public function required(array $data, string $field, string $label): self {
        if (!isset($data[$field]) || trim((string)$data[$field]) === '') $this->errors[$field] = "$label is required.";
        return $this;
    }
    public function email(array $data, string $field, string $label): self {
        if (!empty($data[$field]) && !filter_var($data[$field], FILTER_VALIDATE_EMAIL)) $this->errors[$field] = "$label must be a valid email.";
        return $this;
    }
    public function phone(array $data, string $field, string $label): self {
        if (!empty($data[$field]) && !preg_match('/^[+0-9 ()-]{7,20}$/', (string)$data[$field])) $this->errors[$field] = "$label has an invalid format.";
        return $this;
    }
    public function dateNotFuture(array $data, string $field, string $label): self {
        if (!empty($data[$field]) && (!strtotime((string)$data[$field]) || strtotime((string)$data[$field]) > time())) $this->errors[$field] = "$label cannot be in the future.";
        return $this;
    }
    public function dateNotPast(array $data, string $field, string $label): self {
        if (!empty($data[$field]) && strtotime((string)$data[$field]) < strtotime(date('Y-m-d'))) $this->errors[$field] = "$label cannot be in the past.";
        return $this;
    }
    public function minLength(array $data, string $field, int $min, string $label): self {
        if (isset($data[$field]) && mb_strlen(trim((string)$data[$field])) < $min) $this->errors[$field] = "$label must have at least $min characters.";
        return $this;
    }
    public function errors(): array { return $this->errors; }
    public function validate(): void {
        if ($this->errors) throw new ValidationException($this->errors);
    }
}
