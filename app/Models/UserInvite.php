<?php

namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserInvite
 * @property string $name
 * @property string $email
 * @property string $token
 * @property int    $invited_by_id
 * @property Carbon $invited_at
 * @package App\Models
 * @method static where(string $string1, string $string2, int $string3)
 */
class UserInvite extends Model
{
	use HasRelationships;
	/*** @var int */
	public const INVITE_LIFETIME_MIN = 120;

	/*** @var string[] */
	protected $dates = ['created_at', 'updated_at', 'invited_at'];

	/*** @return BelongsTo */
	public function invited_by(): BelongsTo
	{
		return $this->belongsTo(User::class, 'invited_by_id', 'id');
	}

	/*** @return string */
	public function getName(): string
	{
		return $this->name;
	}

	/*** @param string $name */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/*** @return string */
	public function getEmail(): string
	{
		return $this->email;
	}

	/*** @param string $email */
	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	/*** @return string */
	public function getToken(): string
	{
		return $this->token;
	}

	/*** @param string $token */
	public function setToken(string $token): void
	{
		$this->token = $token;
	}

	/*** @return int */
	public function getInvitedById(): int
	{
		return $this->invited_by_id;
	}

	/*** @param int $invitedById */
	public function setInvitedById(int $invitedById): void
	{
		$this->invited_by_id = $invitedById;
	}

	/*** @return Carbon */
	public function getInvitedAt(): Carbon
	{
		return $this->invited_at;
	}

	/*** @param Carbon $invitedAt */
	public function setInvitedAt(Carbon $invitedAt): void
	{
		$this->invited_at = $invitedAt;
	}
}
