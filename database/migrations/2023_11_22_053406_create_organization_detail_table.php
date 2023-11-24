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
        Schema::create('organizationdetail', function (Blueprint $table) {
            $table->increments('organization_detail_id');
            $table->integer('organization_id')->index('organization_id');
            $table->string('company_name');
            $table->string('application_name')->nullable();
            $table->integer('country_id')->index('country_id')->nullable();
            $table->integer('province_id')->index('province_id')->nullable();
            $table->string('address')->nullable();
            $table->integer('postal_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('fax')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account')->nullable();
            $table->string('bank_account_address')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->integer('number_of_employees')->nullable();
            $table->string('website_company')->nullable();
            $table->string('email_company')->nullable();
            $table->string('instagram_account')->nullable();
            $table->string('twitter_account')->nullable();
            $table->string('facebook_account')->nullable();
            $table->string('linkedin_account')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('background_login')->nullable();
            $table->string('dokumen_logo')->nullable();
            $table->dateTime('created_date');
            $table->dateTime('modified_date');
            $table->string('created_by', 100);
            $table->string('modified_by', 100);

            $table->unique('organization_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organizationdetail');
    }
};
