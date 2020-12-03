<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance_actions', function (Blueprint $table) {
            $table->id(); 
            $table->auth();     
            $table->text('details')->nullable();     
            $table->longPrice('cost')->default(0.00);     
            $table->foreignId('issue_id')->constrained('maintenance_issues');     
            $table->timestamp('completed_at')->nullable();
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
        Schema::dropIfExists('maintenance_actions');
    }
}
