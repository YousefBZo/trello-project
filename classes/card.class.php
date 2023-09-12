<?php

class Card extends Entity
{
    public function __construct($dbc)
    {
        parent::__construct($dbc, 'cards');

    }
    protected function initFields()
    {

        $this->fields = [
            'id',
            'title',
            'created_at',
            'updated_at',
            'board_id',
            'list_id',
            'user_id',
            'archive'
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