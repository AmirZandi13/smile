<?php

namespace App\Models;

use App\Constants\Tables;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = Tables::ACCOUNTS;

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'account_type_id',
        'account_number',
        'date_opened',
        'balance'
    ];

    /**
     * @var string[]
     */
    protected $with = [
        'user',
        'accountType',
        'accountCards'
    ];

    /**
     * @var string[]
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function accountType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AccountCard::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accountCards(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AccountCard::class);
    }
}
