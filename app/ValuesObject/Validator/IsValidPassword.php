<?php

namespace App\ValuesObject\Validator;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Str;

/**
 * Class IsValidPassword
 * @package App\Rules
 */
class IsValidPassword implements Rule
{
	/**
	 * Determine if the Length Validation Rule passes.
	 * @var boolean
	 */
	public bool $lengthPasses = TRUE;

	/**
	 * Determine if the Uppercase Validation Rule passes.
	 * @var boolean
	 */
	public bool $uppercasePasses = TRUE;

	/**
	 * Determine if the Numeric Validation Rule passes.
	 * @var boolean
	 */
	public bool $numericPasses = TRUE;

	/**
	 * Determine if the Special Character Validation Rule passes.
	 * @var boolean
	 */
	public bool $specialCharacterPasses = TRUE;

	/*** @var integer|null */
	public ?int $userId;

	/*** @var int */
	private int $minLength = 10;

	/**
	 * IsValidPassword constructor.
	 * @param int|NULL $userId
	 */
	public function __construct(int $userId = NULL)
	{
		$this->userId = $userId;
	}

	/**
	 * Determine if the validation rule passes.
	 * @param       $attribute
	 * @param mixed $value
	 * @return bool
	 */
	public function passes($attribute, $value): bool
	{
		$this->lengthPasses           = (Str::length($value) >= $this->minLength);
		$this->uppercasePasses        = (Str::lower($value) !== $value);
//		$this->numericPasses          = ((bool) preg_match('/\d/', $value));
		$this->specialCharacterPasses = ((bool) preg_match('/[!@#$%^&*]/', $value));
		return ($this->lengthPasses && $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses);
	}

	/**
	 * Get the validation error message.
	 * @return string
	 */
	public function message(): string
	{
		switch (TRUE) {
			case ! $this->uppercasePasses && $this->numericPasses && $this->specialCharacterPasses:
				return 'The :attribute must be at least ' . $this->minLength . ' characters and contain at least one uppercase character';
			case ! $this->numericPasses && $this->uppercasePasses && $this->specialCharacterPasses:
				return 'The :attribute must be at least ' . $this->minLength . ' characters and contain at least one number';
			case ! $this->specialCharacterPasses && $this->uppercasePasses && $this->numericPasses:
				return 'The :attribute must be at least ' . $this->minLength . ' characters and contain at least one special character (!@#$%^&*)';
			case ! $this->uppercasePasses && ! $this->numericPasses && $this->specialCharacterPasses:
				return 'The :attribute must be at least ' . $this->minLength . ' characters and contain at least one uppercase character and one number';
			case ! $this->uppercasePasses && ! $this->specialCharacterPasses && $this->numericPasses:
				return 'The :attribute must be at least ' . $this->minLength . ' characters and contain at least one uppercase character and one special character (!@#$%^&*)';
			case ! $this->uppercasePasses && ! $this->numericPasses && ! $this->specialCharacterPasses:
				return 'The :attribute must be at least ' . $this->minLength . ' characters and contain at least one uppercase character, one number, and one special character (!@#$%^&*)';
			default:
				return 'The :attribute must be at least ' . $this->minLength . ' characters';
		}
	}
}
