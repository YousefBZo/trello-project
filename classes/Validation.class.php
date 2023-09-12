<?php
class Validation
{
    private $rules;
    private $errorMessages = [];

    public function addRule(Validate $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }
    public function validate($value)
    {
        foreach ($this->rules as $rule) {
            $ruleValidation = $rule->validateRule($value);
            if (!$ruleValidation) {
                $this->errorMessages[] = $rule->getErrorMessage();
                return false;
            }
        }

        return true;
    }
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}