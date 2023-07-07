<?php

namespace App\ValuesObject\Validator;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Class DefaultValidator
 * @package App\ValuesObject
 */
class DefaultValidator
{
	/**
	 * @param Request $request
	 * @param User    $user
	 * @return Application|Factory|Validator
	 */
	public static function getPasswordValidator(Request $request, User $user)
	{
		$validator = validator(
			$request->only('current_password', 'new_password', 'confirm_password'),
			[
				'current_password' => 'required|string',
				'new_password'     => [
					'required',
					'string',
					'min:2',
					new IsValidPassword($user->getId()),
					'different:current_password',
				],
				'confirm_password' => 'required_with:new_password|same:new_password|string|min:10',
			],
			[
				'confirm_password.required_with' => 'A confirmed password is required.',
			]
		);
		if ( ! Hash::check($request->get('current_password'), $user->getPassword())) {
			$validator->after(
				function($validator) {
					$validator->errors()->add('current_password', 'Your current password is incorrect');
				}
			);
		}
		return $validator;
	}

}
