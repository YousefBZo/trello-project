<?php

class Board extends Entity
{
    public function __construct($dbc)
    {
        parent::__construct($dbc, 'boards');

    }
    protected function initFields()
    {

        $this->fields = [
            'id',
            'title',
            'visibility',
            'created_at',
            'updated_at',
            'slug',
            'image',
            'star',
            'user_id',
            'is_close'
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