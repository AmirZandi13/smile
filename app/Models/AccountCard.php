<?php

namespace App\Models;

use App\Constants\Tables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountCard extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = Tables::ACCOUNT_CARDS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'account_id',
        'number',
        'cvv2',
        'expire_date'
    ];
}
