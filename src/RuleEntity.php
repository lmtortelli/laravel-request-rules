<?php


namespace Bachilli\RequestRules;

use Illuminate\Foundation\Http\FormRequest;


class RuleEntity
{
    protected $rules = [];

    protected $excludeList = [];

    protected $fieldName = null;

    protected $validations = null;

    public function __construct(FormRequest $formRequest, $fieldName, $validations)
    {
        $this->rules = $formRequest->rules();
        $this->setFieldName($fieldName);
        $this->validations = $validations;
    }

    public function get()
    {
        $rules = [];

        if ($this->validations) {
            $rules[$this->fieldName] = $this->validations;
        }

        foreach ($this->rules as $ruleField => $ruleValidations) {
            $rules["{$this->fieldName}.$ruleField"] = $ruleValidations;
        }

        return $rules;
    }

    protected function setFieldName(string $fieldName)
    {
        $this->fieldName = $fieldName;

        if (strpos($fieldName, '*') !== false) {
            $this->fieldName = str_replace('.*', '', $fieldName);
        }
    }

    public function only(array $only) : self
    {
        foreach ($this->rules as $ruleField => $ruleValidation) {
            if (!in_array($ruleField, $only)) {
                unset($this->rules[$ruleField]);
            }
        }

        return $this;
    }

    public function except(array $except) : self
    {
        foreach ($this->rules as $ruleField => $ruleValidation) {
            if (in_array($ruleField, $except)) {
                unset($this->rules[$ruleField]);
            }
        }

        return $this;
    }
}
