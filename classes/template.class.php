<?php

class Template extends Entity
{
    public function __construct($dbc)
    {
        parent::__construct($dbc, 'template');

    }
    protected function initFields()
    {

        $this->fields = [
            'id',
            'title',
            'slug',
            'created_at',
            'updated_at',
            'board_id',
            'user_id',
            'image',
            'category'
        ];
    }

    function checkFields($value)
    {
        return trim(htmlspecialchars($value));
    }
}