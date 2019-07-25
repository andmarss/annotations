<?php

namespace App\Format;

class XML extends BaseFormat implements FormatInterface{

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

    public function setData(array $data): void
    {
       $this->data = $data;
    }

}