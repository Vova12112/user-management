<?php

namespace App\ValuesObject\Factories;

use App\Models\User;
use App\Models\UserInvite;
use App\ValuesObject\SecretKeyGenerator;

/**
 * Class UserFactory
 * @package App\ValuesObject\Factories
 */
class UserFactory
{
	/**
	 * @param User|null $user
	 * @param string    $name
	 * @param string    $email
	 * @return UserInvite
	 */
	public static function invite(?User $user, string $name, string $email): UserInvite
	{
		$invite = new UserInvite();
		$invite->setName($name);
		$invite->setEmail($email);
		$invite->setToken(generateSecretKey(128));
		$invite->setInvitedById(1);
		$invite->setInvitedAt(now('UTC'));
		$invite->save();
		$invite->refresh();
		return $invite;
	}

	/**
	 * @param UserInvite  $invite
	 * @param string      $password
	 * @param string|NULL $name
	 * @return User
	 */
	public static function create(UserInvite $invite, string $password, ?string $name = NULL): User
	{
		$user = new User();
		$user->setName($name ?? $invite->getName());
		$user->setEmail($invite->getEmail());
		$user->setPassword(bcrypt($password));
		$user->save();
		$user->refresh();
		return $user;
	}

}