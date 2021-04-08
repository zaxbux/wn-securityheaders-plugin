<?php namespace Zaxbux\SecurityHeaders\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class TableCreateZaxbuxSecurityheadersReportingCsp extends Migration
{
    public function up()
    {
        Schema::create('zaxbux_securityheaders_reporting_csp', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('original_data')->nullable();
            $table->string('action', 16)->nullable();
            $table->string('blocked_uri')->nullable();
            $table->string('disposition', 16)->nullable();
            $table->string('document_uri')->nullable();
            $table->text('effective_directive')->nullable();
            $table->text('original_policy')->nullable();
            $table->string('referrer')->nullable();
            $table->string('script_sample', 128)->nullable();
            $table->smallInteger('status_code')->nullable()->unsigned();
            $table->text('violated_directive')->nullable();
            $table->text('user_agent', 1024)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('zaxbux_securityheaders_reporting_csp');
    }
}
