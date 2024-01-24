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
        'updated_at' => "string",
        'id' => "null"])
    ]
    public static function fakeWithId(): array
    {
        return  [
            'username' => uniqid() . "UserName",
            'email' => uniqid() . '@example.com',
            'birth_date' => "1995-12-30",
            'registration_date' => "2023-10-08",
            'is_active' => true,
            'created_at' => "2023-10-10",
            'updated_at' => "2023-12-30",
            'id' => null
        ];
    }

    #[ArrayShape([
        'username' => "string",
        'email' => "string",
        'birth_date' => "string",
        'registration_date' => "string",
        'is_active' => "string",
        'created_at' => "string",
        'updated_at' => "string"])
    ]
    public static function fake(): array
    {
        return  [
            'username' => uniqid() . "UserName",
            'email' => uniqid() . '@example.com',
            'birth_date' => "1995-12-30",
            'registration_date' => "2023-10-08",
            'is_active' => true,
            'created_at' => "2023-10-10",
            'updated_at' => "2023-12-30"
        ];
    }

    #[ArrayShape([
        'username' => "string",
        'email' => "string",
        'birth_date' => "string",
        'registration_date' => "string",
        'is_active' => "string",
        'created_at' => "string",
        'updated_at' => "string"])
    ]
    public static function fakeWithQuotes(): array
    {
        return [
            'username' => "'" . uniqid() . "UserName'",
            'email' => "'" . uniqid() . '@example.com' . "'",
            'birth_date' => "'1995-12-30'",
            'registration_date' => "'2023-10-08'",
            'is_active' => 'true',
            'created_at' => "'2023-10-10'",
            'updated_at' => "'2023-12-30'",
        ];
    }


    public static function fakeArray(): array
    {
        return [
            "'" . uniqid() . "UserName'",
            "'" . uniqid() . '@example.com' . "'",
            "'1995-12-30'",
            "'2023-10-08'",
            'true',
            "'2023-10-10'",
            "'2023-12-30'",
        ];
    }
}