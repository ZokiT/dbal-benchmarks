<?php

namespace App;

use JetBrains\PhpStorm\ArrayShape;

trait User
{
    #[ArrayShape([
        'username' => "string",
        'email' => "string",
        'birth_date' => "string",
        'registration_date' => "string",
        'is_active' => "string",
        'created_at' => "string",
        'updated_at' => "string"])
    ]
    public static function fake($wrapDataInQuotes = true): array
    {
        $wrapDataInQuotes ?
            $data = [
            'username' => "'" . uniqid() . "UserName'",
            'email' => "'" . uniqid() . '@example.com' . "'",
            'birth_date' => "'1995-12-30'",
            'registration_date' => "'2023-10-08'",
            'is_active' => 'true',
            'created_at' => "'2023-10-10'",
            'updated_at' => "'2023-12-30'",
             ] :
            $data = [
            'username' => uniqid() . "UserName",
            'email' => uniqid() . '@example.com',
            'birth_date' => "1995-12-30",
            'registration_date' => "2023-10-08",
            'is_active' => true,
            'created_at' => "2023-10-10",
            'updated_at' => "2023-12-30",
             'id' => null
            ];

        return $data;
    }
}