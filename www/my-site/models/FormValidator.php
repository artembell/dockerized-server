<?php

namespace models;

class FormValidator
{
    private $inputNamesToValidate;
    private $validationData;
    private $pathRoot;

    public function __construct(array $inputNamesToValidate, string $root)
    {
        $this->inputNamesToValidate = $inputNamesToValidate;
        $this->validationData = [];
        $this->pathRoot = $root;
    }

    public function validateInput($data): void
    {
        foreach ($this->inputNamesToValidate as $value) {
            $validateFunc = $this->resolveIntoValidateFunctionName($value);

            $inputValue = empty($data[$value]) ? '' : $data[$value];
            $this->validationData[$value] = [
                'error-message' => $this->$validateFunc($inputValue)
            ];
        }
    }

    public function getErrorsInfo(string $inputKey): string
    {
        return $this->validationData[$inputKey]['error-message'];
    }

    public function isCorrectInput(string $inputName): bool
    {
        return empty($this->validationData[$inputName]['error-message']);
    }

    public function getCorrectnessClass(string $inputKey): string
    {
        return $this->isCorrectInput($inputKey) ? 'no-errors' : 'has-error';
    }

    private function validate(string $inputKey, $data): string
    {
        $validateFunctionName = resolveIntoValidateFunctionName($inputKey);
        return $validateFunctionName($data);
    }

    private function resolveIntoValidateFunctionName(string $inputKey): string
    {
        return 'validate'.ucfirst($inputKey).'Input';
    }

    private function validatePathInput(string $path): string
    {
        if (empty(trim($path))) {
            return 'Path to the folder should not be empty';
        } elseif (preg_match('/\s+/', $path)) {
            return 'Path should not contain whitespaces';
        } elseif (!preg_match('/^\//', $path)) {
            return 'Path should start with /';
        } elseif (!file_exists($this->pathRoot.$path)) {
            return 'Such folder does not exist';
        } elseif (!is_dir($this->pathRoot.$path)) {
            return 'Path points not to a folder';
        } else {
            return '';
        }
    }
}
