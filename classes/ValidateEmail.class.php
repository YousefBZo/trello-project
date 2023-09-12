<?php
class ValidateEmail implements Validate
{

    public function validateRule($value)
    {
        $email = filter_var($value, FILTER_SANITIZE_EMAIL);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    function getErrorMessage()
    {
        return "Email format is not correct!.";
    }
}