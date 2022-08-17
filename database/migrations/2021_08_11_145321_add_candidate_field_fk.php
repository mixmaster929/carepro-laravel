<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCandidateFieldFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

       $candidateFields = \App\CandidateField::get();
       foreach($candidateFields as $field){
           $id = $field->candidate_field_group_id;
           if(!\App\CandidateFieldGroup::find($id)){
               $obj = \App\CandidateField::find($field->id);
               $obj->delete();
           }
       }

        Schema::table('candidate_fields', function (Blueprint $table) {
            $table->foreign('candidate_field_group_id')->references('id')->on('candidate_field_groups')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_fields', function (Blueprint $table) {
            //
        });
    }
}
