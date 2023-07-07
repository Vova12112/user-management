<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*** Class CreateUserInvitesTable */
class CreateUserInvitesTable extends Migration
{
	/**
	 * Run the migrations.
	 * @return void
	 */
	public function up(): void
	{
		Schema::create(
			'user_invites',
			static function(Blueprint $table) {
				$table->id();
				$table->string('name');
				$table->string('email');
				$table->string('token', 128);
				$table->foreignId('invited_by_id')->constrained('users');
				$table->timestamp('invited_at');
				$table->timestamp('created_at')->useCurrent();
				$table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
			}
		);
	}
}
