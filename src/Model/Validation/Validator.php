<?php

namespace Apex\src\Model\Validation;

use Apex\src\App;
use Rakit\Validation\RuleQuashException;

class Validator extends \Rakit\Validation\Validator
{
    /**
     * @throws RuleQuashException
     */
    public function __construct(array $messages = [])
    {
        parent::__construct($messages);
        $this->addValidator('unique', new UniqueRule(app()->db->pdo));
    }
}