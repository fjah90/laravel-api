<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrollingToStudentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('enrolling_to_students', function(Blueprint $table)
		{
			// $table->id();

			$table->unsignedBigInteger('user_id');
			$table->unsignedBigInteger('course_id');

			$table->foreign('user_id')
			      ->references('id')->on('users')
			      ->onDelete('cascade')
			      ->onUpdate('cascade');

			$table->foreign('course_id')
			      ->references('id')->on('courses')
			      ->onDelete('cascade')
			      ->onUpdate('cascade');

			$table->primary(['user_id', 'course_id']);
			
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('enrolling_to_students');
	}

}
