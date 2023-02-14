<?php

namespace Apex\src\Views\Forms;

use Apex\src\Model\Model;

class Field
{
    /**
     * @param Model $model Model
     * @param string $attr
     * @param array $options type=>'', class=>'', id=>''
     */
    public function __construct(public Model $model, public string $attr, public array $options = [])
    {
    }

    public function __toString(): string
    {
        $inputHtmlOptions = '';
        foreach ($this->options as $key => $value) {
            $inputHtmlOptions .= " $key='$value' ";
        }
        return sprintf(
            '
            <input
                        name="%s"
                        value="%s"
                        %s
                />
            ',
            $this->attr,
            $this->model->{$this->attr},
            $inputHtmlOptions
        );
    }

}