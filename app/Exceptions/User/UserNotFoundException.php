<?php

namespace App\Exceptions\User;

use Exception;

/**
 * Class UserNotFoundException
 * @package App\Exceptions\User
 */
class UserNotFoundException extends Exception
{
	/*** @var string */
	protected $message = 'User Not Found';
}