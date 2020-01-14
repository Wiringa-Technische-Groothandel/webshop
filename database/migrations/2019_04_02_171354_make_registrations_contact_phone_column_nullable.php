<?php

declare(strict_types=1);

namespace Database\Migrations;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeRegistrationsContactPhoneColumnNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (\DB::getDriverName() === 'mysql') {
                // phpcs:disable
                \DB::statement('ALTER TABLE registrations CHANGE `contact-phone` `contact-phone` VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
                \DB::statement('ALTER TABLE registrations CHANGE `other-order-confirmation` `other-order-confirmation` TINYINT(1) DEFAULT 0 NOT NULL COLLATE utf8mb4_unicode_ci');
                \DB::statement('ALTER TABLE registrations CHANGE `other-mail-productfile` `other-mail-productfile` TINYINT(1) DEFAULT 0 NOT NULL COLLATE utf8mb4_unicode_ci');
                // phpcs:enable
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            if (\DB::getDriverName() === 'mysql') {
                // phpcs:disable
                \DB::statement('ALTER TABLE registrations CHANGE `contact-phone` `contact-phone` VARCHAR(255) COLLATE utf8mb4_unicode_ci');
                \DB::statement('ALTER TABLE registrations CHANGE `other-order-confirmation` `other-order-confirmation` TINYINT(1) COLLATE utf8mb4_unicode_ci');
                \DB::statement('ALTER TABLE registrations CHANGE `other-mail-productfile` `other-mail-productfile` TINYINT(1) COLLATE utf8mb4_unicode_ci');
                // phpcs:enable
            }
        });
    }
}
