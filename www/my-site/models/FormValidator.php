<?php

namespace models;

class FormValidator
{
    private $inputNamesToValidate;
    private $validationData;
    private $pathRoot;

    private $validateFunctions;

    public function __construct(array $inputNamesToValidate, string $root)
    {
        $this->inputNamesToValidate = $inputNamesToValidate;
        $this->validationData = [];
        $this->pathRoot = $root;

        $this->setValidateFunctions();
    }

    private function setValidateFunctions(): void
    {
        $validateFolderPath = function (string $path): string {
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
        };

        $this->validateFunctions['path'] = $validateFolderPath;
    }

    private function getValidateFunction(string $inputName): callable
    {
        return $this->validateFunctions[$inputName];
    }

    public function validateInput(array $data): void
    {
        foreach ($this->inputNamesToValidate as $inputName) {
            $validateFunc = $this->getValidateFunction($inputName);

            $inputValue = empty($data[$inputName]) ? '' : $data[$inputName];
            $this->validationData[$inputName] = [
                'error-message' => $validateFunc($inputValue)
            ];
        }
    }

    public function getErrorsInfo(string $inputKey): string
    {
        return $this->validationData[$inputKey]['error-message'];
    }

    public function formHasCorrectInput(): bool
    {
        foreach ($this->inputNamesToValidate as $inputName) {
            if (!$this->fieldHasCorrectInput($inputName)) {
                return false;
            }
        }
        return true;
    }

    private function fieldHasCorrectInput(string $inputName): bool
    {
        return empty($this->validationData[$inputName]['error-message']);
    }

    public function getCorrectnessClass(string $inputName): string
    {
        return $this->fieldHasCorrectInput($inputName) ? 'no-errors' : 'has-error';
    }
}
