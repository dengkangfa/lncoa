<?php

namespace App\Providers;

use Germey\Geetest\Geetest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class GeetestServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(Request $request)
	{
		$this->loadViewsFrom(__DIR__ . '/views', 'geetest');

		$this->publishes([
			__DIR__ . '/views' => base_path('resources/views/vendor/geetest'),
			__DIR__ . '/config.php' => config_path('geetest.php'),
		]);

		Route::get('geetest', 'App\Http\Controllers\Auth\GeetestCaptchaController@getGeetest');

		Validator::extend('geetest', function () use ($request) {
			list($geetest_challenge, $geetest_validate, $geetest_seccode, $gtserver, $user_id) = array_values($request->only('geetest_challenge', 'geetest_validate', 'geetest_seccode', 'gtserver', 'user_id'));
			if ($gtserver == 1) {
				if (Geetest::successValidate($geetest_challenge, $geetest_validate, $geetest_seccode, $user_id)) {
					return true;
				}
				return false;
			} else {
				if (Geetest::failValidate($geetest_challenge, $geetest_validate, $geetest_seccode, $user_id)) {
					return true;
				}
				return false;
			}
		});

		Blade::extend(function ($value) {
			return preg_replace('/@define(.+)/', '<?php ${1}; ?>', $value);
		});

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('geetest', function () {
			return $this->app->make('Germey\Geetest\GeetestLib');
		});
	}
}
