<?php

namespace App\Models;

use App;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\TwoFactorAuthenticatable;

/**
 * Class User
 * @property int         $id
 * @property string|NULL $name
 * @property string      $email
 * @property int         $invited_by_id
 * @property bool        $root
 * @property string|NULL $password
 * @property string|NULL $two_factor_secret
 * @property string|NULL $two_factor_recovery_codes
 * @property Carbon|NULL $two_factor_confirmed_at
 * @property Carbon|NULL $last_activity_at
 * @property Carbon      $created_at
 * @property Carbon      $updated_at
 * @property Carbon|NULL $deleted_at
 * @package App\Models
 * @method static where(string $string1, string $string2, int $string3)
 */
class User extends Authenticatable
{
	use SoftDeletes, TwoFactorAuthenticatable;

	/*** @var string[] */
	protected $dates = ['last_activity_at', 'created_at', 'updated_at'];

	/*** @return BelongsTo */
	public function invited_by(): BelongsTo
	{
		return $this->belongsTo(__CLASS__, 'invited_by_id', 'id');
	}

	/*** @return int */
	public function getId(): int
	{
		return $this->id;
	}

	/*** @return string|NULL */
	public function getName(): ?string
	{
		return $this->name;
	}

	/*** @param string|NULL $name */
	public function setName(?string $name): void
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

	/*** @return bool */
	public function isRoot(): bool
	{
		return $this->root;
	}

	/*** @param bool $root */
	public function setRoot(bool $root): void
	{
		$this->root = $root;
	}

	/*** @return string|NULL */
	public function getPassword(): ?string
	{
		return $this->password;
	}

	/*** @param string|NULL $password */
	public function setPassword(?string $password): void
	{
		$this->password = $password;
	}

	/*** @return string|NULL */
	public function getTwoFactorSecret(): ?string
	{
		return $this->two_factor_secret;
	}

	/*** @param string|NULL $two_factor_secret */
	public function setTwoFactorSecret(?string $two_factor_secret): void
	{
		$this->two_factor_secret = $two_factor_secret;
	}

	/*** @return string|NULL */
	public function getTwoFactorRecoveryCodes(): ?string
	{
		return $this->two_factor_recovery_codes;
	}

	/*** @param string|NULL $two_factor_recovery_codes */
	public function setTwoFactorRecoveryCodes(?string $two_factor_recovery_codes): void
	{
		$this->two_factor_recovery_codes = $two_factor_recovery_codes;
	}

	/*** @return Carbon|NULL */
	public function getTwoFactorConfirmedAt(): ?Carbon
	{
		return $this->two_factor_confirmed_at;
	}

	/*** @param Carbon|NULL $two_factor_confirmed_at */
	public function setTwoFactorConfirmedAt(?Carbon $two_factor_confirmed_at): void
	{
		$this->two_factor_confirmed_at = $two_factor_confirmed_at;
	}

	/*** @return Carbon */
	public function getLastActivityAt(): Carbon
	{
		return $this->last_activity_at;
	}

	/*** @param Carbon $last_activity_at */
	public function setLastActivityAt(Carbon $last_activity_at): void
	{
		$this->last_activity_at = $last_activity_at;
	}

	/*** @return Carbon */
	public function getCreatedAt(): Carbon
	{
		return $this->created_at;
	}

	/*** @return Carbon */
	public function getUpdatedAt(): Carbon
	{
		return $this->updated_at;
	}

	/*** @return Carbon|NULL */
	public function getDeletedAt(): ?Carbon
	{
		return $this->deleted_at;
	}

	/*** @return void */
	public function enableTwoFactorAuth(): void
	{
		(new EnableTwoFactorAuthentication(App::make(TwoFactorAuthenticationProvider::class)))($this);
	}

	/*** @return void */
	public function disableTwoFactorAuth(): void
	{
		if ($this->hasEnabledTwoFactorAuthentication()) {
			(new DisableTwoFactorAuthentication())($this);
		}
	}

	/***
	 * @param string $code
	 * @return void
	 * @throws ValidationException
	 */
	public function checkTwoFactorAuth(string $code): void
	{
		(new ConfirmTwoFactorAuthentication(App::make(TwoFactorAuthenticationProvider::class)))($this, $code);
	}

	/**
	 * @param string $code
	 * @return bool
	 */
	public function confirmTwoFactorAuth(string $code): bool
	{
		try {
			$this->checkTwoFactorAuth($code);
		} catch (Exception $e) {
			return FALSE;
		}
		return TRUE;
	}

	/*** @return bool */
	public function isConfirmedTwoFactor(): bool
	{
		return $this->two_factor_confirmed_at !== NULL;
	}

	/***
	 * @param string $password
	 */
	public function updatePassword(string $password): void
	{
		$this->setPassword(bcrypt($password));
		$this->save();
	}

	/*** @return void */
	public function updateRecoveryCodes(): void
	{
		foreach ($this->recoveryCodes() as $recoveryCode) {
			$this->replaceRecoveryCode($recoveryCode);
		}
	}

}
