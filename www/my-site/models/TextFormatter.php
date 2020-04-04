<?php

namespace models;

class TextFormatter
{
    private $patterns;

    public function __construct()
    {
        $this->defineRules();
    }

    private function defineRules(): void
    {
        $this->rules = [
            'number' => [
                '/(\d+[\.,])?\d+/',
                function ($matches) {
                    $numberString = $matches[0];
                    $dotPos = strpos($matches[0], '.');
                    $commaPos = strpos($matches[0], ',');

                    if ($dotPos !== false || $commaPos !== false) {
                        if ($commaPos !== false) {
                            $numberString[$commaPos] = '.';
                        }
                        $numberFloat = floatval($numberString);
                        $numberFloatRounded = round($numberFloat, 2);
                        return '<span class="formatted-fraction">' . $numberFloatRounded . '</span>';
                    }
                    return '<span class="formatted-int">' . $matches[0] . '</span>';
                },
            ],

            'ucword' => [
                '/[A-ZА-Я]\w*/u',
                function ($matches) {
                    return '<span class="formatted-ucword">' . $matches[0] . '</span>';
                },
            ],
        ];
    }

    public function format(string $text): string
    {
        $formattedText = $text;
        foreach ($this->rules as $rule) {
            $pattern = $rule[0];
            $formatFunction = $rule[1];

            $formattedText = preg_replace_callback($pattern, $formatFunction, $formattedText);
        }

        return '<p>' . $formattedText . '</p>';
    }
}
