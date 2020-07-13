<?php

namespace App\Rules;

class RegisterIncubatorRules
{
    /**
     * Return the defined rules
     *
     * @return array
     */
    public static function rules()
    {
        return [
            'email' => 'required|min:1',
            'password' => 'required|min:1',
        ];
    }
}