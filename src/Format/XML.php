<?php

namespace App\Format;

class XML extends BaseFormat {

    public function convert(): string
    {
        /**
         * @var string $result
         */
        $result = '';

        foreach ($this->data as $key => $value) {
            $result .= '<' . $key . '>' . $value . '</' . $key . '>';
        }

        return htmlspecialchars($result);
    }

}