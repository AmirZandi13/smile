<?php

use App\Constants\Tables;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Tables::ACCOUNT_CARDS, function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('account_id')->index();

            $table->string('number')->unique();
            $table->unsignedInteger('cvv2');
            $table->timestamp('expire_date')->default(Carbon::now()->addYears(2));

            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');

            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Tables::ACCOUNT_CARDS);
    }
};
