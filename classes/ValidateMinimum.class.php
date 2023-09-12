<?php
class ValidateMinimum implements Validate
{



    private $minimum;

    public function __construct($minimum)
    {
        $this->minimum = $minimum;
    }


    function validateRule($value)
    {
        if (strlen($value) < $this->minimum) {
            return false;
        }
        return true;

    }

    public function getErrorMessage()
    {
        return "Minimum value is under " . $this->minimum;
    }
}