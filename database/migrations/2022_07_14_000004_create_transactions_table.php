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
        Schema::create(Tables::TRANSACTIONS, function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('origin_account_id')->index();
            $table->unsignedBigInteger('destination_account_id')->index();

            $table->timestamp('date')->default(Carbon::now());
            $table->string('amount');


            $table->foreign('origin_account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->foreign('destination_account_id')->references('id')->on('accounts')->onDelete('cascade');

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
        Schema::dropIfExists(Tables::TRANSACTIONS);
    }
};
