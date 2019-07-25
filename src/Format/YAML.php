<?php

namespace App\Format;

class YAML extends BaseFormat implements NamedFormatInterface, FormatInterface {
    public function convert(): string
    {
        /**
         * @var string $result
         */
        $result = '';

        foreach ($this->data as $key => $value) {
            $result .= $key . ': ' . $value . "\n";
        }

        return htmlspecialchars($result);
    }

    public function getName(): string
    {
       return 'YAML';
    }

    public function setData(array $data): void
    {
       $this->data = $data;
    }
}