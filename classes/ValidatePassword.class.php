<?php
class ValidatePassword implements Validate
{
    private $password;
    private $cpassword;
    public function __construct($password, $cpassword)
    {
        $this->password = $password;
        $this->cpassword = $cpassword;
    }
    function validateRule($value)
    {
        
        if ($this->password === $this->cpassword) {
            return true;
        }

        return false;
    }




    function getErrorMessage()
    {
        return "Passwords does not match.";
    }
}