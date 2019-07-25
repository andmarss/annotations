<?php

namespace App\Format;

class YAML extends BaseFormat implements NamedFormatInterface {
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
}