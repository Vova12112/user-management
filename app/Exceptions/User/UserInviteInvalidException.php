<?php

namespace App\Exceptions\User;

use Exception;

/**
 * Class UserNotFoundException
 * @package App\Exceptions\User
 */
class UserInviteInvalidException extends Exception
{
	/*** @var string */
	protected $message = 'User Invite Is Invalid';
}