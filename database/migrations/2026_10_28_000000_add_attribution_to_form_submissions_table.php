<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Origen de marketing del lead: click IDs de Google/Meta, UTMs, URL de
     * aterrizaje y referrer. Todo nullable: un lead sin atribución es válido.
     */
    public function up(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->string('gclid')->nullable()->after('closure_reason');
            $table->string('gbraid')->nullable()->after('gclid');
            $table->string('wbraid')->nullable()->after('gbraid');
            $table->string('fbclid')->nullable()->after('wbraid');
            $table->string('utm_source')->nullable()->after('fbclid');
            $table->string('utm_medium')->nullable()->after('utm_source');
            $table->string('utm_campaign')->nullable()->after('utm_medium');
            $table->string('utm_term')->nullable()->after('utm_campaign');
            $table->string('utm_content')->nullable()->after('utm_term');
            $table->text('landing_url')->nullable()->after('utm_content');
            $table->text('referrer')->nullable()->after('landing_url');

            $table->index('gclid');
            $table->index('utm_campaign');
        });
    }

    public function down(): void
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropIndex(['gclid']);
            $table->dropIndex(['utm_campaign']);

            $table->dropColumn([
                'gclid', 'gbraid', 'wbraid', 'fbclid',
                'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
                'landing_url', 'referrer',
            ]);
        });
    }
};
