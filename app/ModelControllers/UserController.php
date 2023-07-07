<?php

namespace App\ModelControllers;

use App\Exceptions\User\UserInviteInvalidException;
use App\Exceptions\User\UserNotFoundException;
use App\ModelControllers\Repositories\UserRepositories;
use App\Models\User;
use App\Models\UserInvite;
use Illuminate\Support\Collection;

/**
 * Class UserController
 * @package App\ModelControllers
 */
class UserController
{
	/*** @var UserRepositories */
	private UserRepositories $repo;

	/*** @return void */
	public function __construct()
	{
		$this->repo = new UserRepositories();
	}

	/**
	 * @param int $id
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function findById(int $id): User
	{
		return $this->repo->findById($id);
	}

	/**
	 * @param int $email
	 * @return User
	 * @throws UserNotFoundException
	 */
	public function findByEmail(int $email): User
	{
		return $this->repo->findByEmail($email);
	}

	/**
	 * @param string $token
	 * @return UserInvite
	 * @throws UserInviteInvalidException
	 */
	public function findUserInviteByToken(string $token): UserInvite
	{
		return $this->repo->findUserInviteByToken($token);
	}

	/*** @return Collection */
	public function fetchUserInvites(): Collection
	{
		return $this->repo->fetchUserInvites();
	}

	/*** @return Collection */
	public function fetchUsers(): Collection
	{
		return $this->repo->fetchUsers();
	}

}