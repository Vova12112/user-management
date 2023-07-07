<?php

namespace App\ValuesObject\Statuses;

/**
 * Class StatusColorScheme
 * @package App\ValuesObject\Statuses
 */
class StatusColorScheme
{
	/*** @var array */
	public const ALL = [
		'active'      => '#bbf7d099',
		'pending'     => '#d9f99d',
		'initialized' => '#d9f99d',
		'in_progress' => '#bbf7d099',
		'closed'      => '#d1d5db',
		'blinked'     => '#e9d5ff',
		'success'     => '#bbf7d0',
		'failed'      => '#fecdd3',
	];
}