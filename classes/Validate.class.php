<?php
interface Validate
{
    public function validateRule($value);
    public function getErrorMessage();
}