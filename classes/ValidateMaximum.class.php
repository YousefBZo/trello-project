<?php
class ValidateMaximum implements Validate
{



    private $maximum;

    public function __construct($maximum)
    {
        $this->maximum = $maximum;
    }


    function validateRule($value)
    {
        if (strlen($value) > $this->maximum) {
            return false;
        }
        return true;

    }

    public function getErrorMessage()
    {
        return "maximum value is under " . $this->maximum;
    }
}