<?php
class ValidateEmptyFields implements Validate
{

    function validateRule($value)
    {

        if (empty($value)) {
            return false;
        }

        return true;

    }


    function getErrorMessage()
    {
        return "Empty field.";
    }
}