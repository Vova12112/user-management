<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

/**
 * Class FortifyServiceProvider
 * @package App\Providers
 */
class FortifyServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap any application services.
	 * @return void
	 */
	public function boot(): void
	{
		RateLimiter::for('login', static function(Request $request) {
			return Limit::perMinute(5)->by($request->get('email') . $request->ip());
		});
		Fortify::loginView(
			static function() {
				return view('auth.pages.login');
			}
		);
		Fortify::authenticateThrough(static function() {
			return array_filter([
				config('fortify.limiters.login') ? NULL : EnsureLoginIsNotThrottled::class,
				Features::enabled(Features::twoFactorAuthentication()) ? RedirectIfTwoFactorAuthenticatable::class : NULL,
				AttemptToAuthenticate::class,
				PrepareAuthenticatedSession::class,
			]);
		});
	}
}
