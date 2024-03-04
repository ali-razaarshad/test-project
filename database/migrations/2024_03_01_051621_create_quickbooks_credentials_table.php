<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quickbooks_credentials', function (Blueprint $table) {
            $table->id();
            $table->text('client_id')->nullable();
            $table->text('client_secret')->nullable();
            $table->text('redirect_uri')->nullable();
            $table->text('access_token')->nullable();
            $table->string('refresh_token')->nullable();
            $table->string('realm_id')->nullable();
            $table->string('base_url')->nullable();
            $table->string('api_url')->nullable();
            $table->string('others')->nullable();
            $table->integer('status')->default(1)->nullable();
            $table->timestamp('access_token_expires_at',0)->nullable();
            $table->string('access_token_validation_period')->nullable();
            $table->timestamp('refresh_token_expires_at',0)->nullable();
            $table->string('refresh_token_validation_period')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quickbooks_credentials');
    }
};
