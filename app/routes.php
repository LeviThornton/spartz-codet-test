<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::group(array('prefix' => 'v1'), function()
{
	
	/*
	 * setup some routing patterns and controller routes 
	 */

	Route::pattern('id', '[0-9]+');

	// GET AND POST for the above user options
	Route::controller('users/{id}/visits', 'UsersController');
	
	/*
	 * setup some routing patterns and controller routes 
	 */
	Route::pattern('city', '[A-z]+');
	Route::pattern('state', '[A-Z]{2}');

	// List all cities in a state
	Route::get('states/{state}/cities.json', 'StatesController@getStateCities');

	// List cities within a 100 mile radius of a city
	Route::get('states/{state}/cities/{city}.json', 'StatesController@getCitiesByRadius');
	
});
