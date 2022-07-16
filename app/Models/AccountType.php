<?php

namespace App\Models;

use App\Constants\Tables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountType extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = Tables::ACCOUNT_TYPES;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'code'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
}
