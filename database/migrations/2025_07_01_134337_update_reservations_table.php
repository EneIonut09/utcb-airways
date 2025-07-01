<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'passenger_name',
                'passenger_email', 
                'passenger_phone',
                'number_of_passengers',
                'special_requests'
            ]);
            
            $table->string('booking_reference')->unique()->after('id');
            $table->enum('booking_class', ['economy', 'business', 'first'])->default('economy')->after('status');
        });
    }

    public function down()
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->string('passenger_name');
            $table->string('passenger_email');
            $table->string('passenger_phone');
            $table->integer('number_of_passengers');
            $table->text('special_requests')->nullable();
            
            $table->dropColumn(['booking_reference', 'booking_class']);
        });
    }
};