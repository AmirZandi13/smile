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
        Schema::create(Tables::ACCOUNTS, function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('user_id')->index();

            $table->unsignedInteger('account_type_id')->index();

            $table->unsignedInteger('account_number')->unique();
            $table->timestamp('date_opened')->default(Carbon::now());

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('account_type_id')->references('id')->on('account_types')->onDelete('cascade');

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
        Schema::dropIfExists(Tables::ACCOUNTS);
    }
};
