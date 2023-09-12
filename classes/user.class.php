<?php

class User extends Entity
{
    public function __construct($dbc)
    {
        parent::__construct($dbc, 'users');

    }
    protected function initFields()
    {

        $this->fields = [
            'id',
            'name',
            'email',
            'password',
            'is_admin',
            'is_banned',
            'is_active',
            'created_at',
            'updated_at',
            'ip_address',
            'bio',
            'image'

        ];
    }
    // public function matchPassword($password, $cpassword)
    // {
    //     if ($password === $cpassword) {
    //         return true;
    //     }

    //     return false;
    // }
    // function emptyFields($value)
    // {
    //     foreach ($value as $val) {
    //         if (empty($val)) {
    //             return false;
    //         }
    //     }
    //     return true;
    // }
    function checkFields($value)
    {
        return trim(htmlspecialchars($value));
    }
}