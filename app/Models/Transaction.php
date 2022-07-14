<?php

namespace App\Models;

use App\Constants\Tables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = Tables::TRANSACTIONS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'origin_account_id',
        'destination_account_id',
        'date',
        'amount'
    ];
}
