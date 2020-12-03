<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Zareismail\Maintenable\Maintenable;

class CreateMaintenanceIssuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_issues', function (Blueprint $table) {
            $table->id(); 
            $table->auth();  
            $table->labeling('report');
            $table->text('details')->nullable(); 
            $table->enum('risk', array_keys(Maintenable::risks()))->default(Maintenable::SAFE); 
            $table->morphs('maintenable');     
            $table->foreignId('category_id')->constrained('maintenance_categories');     
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
        Schema::dropIfExists('maintenance_issues');
    }
}
