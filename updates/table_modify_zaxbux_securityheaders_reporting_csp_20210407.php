<?php namespace Zaxbux\SecurityHeaders\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateZaxbuxSecurityheadersReportingCsp extends Migration
{
    public function up()
    {
        Schema::table('zaxbux_securityheaders_reporting_csp', function($table)
        {
            $table->text('blocked_uri')->nullable()->change();
            $table->text('document_uri')->nullable()->change();
        });
    }
    
    public function down()
    {
        Schema::table('zaxbux_securityheaders_reporting_csp', function($table)
        {
            $table->string('blocked_uri')->nullable()->change();
            $table->string('document_uri')->nullable()->change();
        });
    }
}
