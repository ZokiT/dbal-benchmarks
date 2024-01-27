<?php

namespace App\codeIgniter\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class User extends Model
{
    use \App\User;
    protected $table      = 'users';
    protected $primaryKey = 'user_id';

    protected $allowedFields = ['username', 'email','registration_date', 'is_active', 'birth_date'];

    public function __construct(?ConnectionInterface $db = null, ?ValidationInterface $validation = null)
    {
        parent::__construct($db, $validation);
    }

}