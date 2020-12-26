<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PriceSymbol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('symbol_prices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date("date");
            $table->string("symbol");
            $table->decimal("price",10,1);
            $table->decimal("volume",15,1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('symbol_prices');
    }
}
