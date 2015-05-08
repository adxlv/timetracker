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


// Blade::setContentTags('{%', '%}'); // for variables and all things Blade
// Blade::setEscapedContentTags('{%%', '%%}'); // for escaped data

Route::filter('apiauth'					, 'HomeController@showApiLogin');

Route::any('/logout' 					, 'HomeController@showLogout');
Route::any('/logoutfromdesktopapp'		, 'HomeController@showLogout_fromDesktopApp');
Route::any('/login', array(
	'as'     => 'auth',
	'uses'   => 'HomeController@showLogin',
)); 
Route::get('/', array(
	'before' => 'auth',
	'uses'   => 'HomeController@index'
));


Route::get('/printinvoice/{id}'				, 'HomeController@pdf_invoice');
Route::get('/printestimate/{id}'			, 'HomeController@pdf_estimate');


Route::group(array(
		'before' => 'auth|crfs',
		'prefix' => 'desktopapp',
	), function() {
		Route::get('/dash'                      , 'HomeController@dashboard');
});
 
Route::group(array(
		'before' => 'apiauth|crfs',
		'prefix' => 'api/v1',
	), function() {
		Route::get('/loggedin'					, 'HomeController@loggedIn');

	 	Route::get ('/clients/myprojects' 		, 'ClientsController@indexMyClients');
		Route::resource('clients' 				, 'ClientsController');
	 	Route::post('/clients/{id}' 			, 'ClientsController@update');
	 	Route::get ('/clients/{id}/withprojects' 	, 'ClientsController@showProjects');
		
		Route::resource('projects' 				, 'ProjectsController');
		Route::post('/projects/{id}' 			, 'ProjectsController@update');
		Route::get('/projects/{id}/estimates' 	, 'ProjectsController@showEstimates');
		Route::get('/projects/{id}/tasks' 		, 'ProjectsController@showTasks');
		Route::get('/projects/{id}/rougetasks'	, 'ProjectsController@showRougeTasks');
		
		Route::resource('estimates'				, 'EstimatesController');
	 	Route::post('/estimates/{id}' 			, 'EstimatesController@update');
		Route::get('/estimates/{id}/tasks' 		, 'EstimatesController@showTasks');
		Route::get('/estimates/{id}/jobroles' 	, 'EstimatesController@showJobRoles');

	 	Route::resource('jobroles' 				, 'JobRolesController');
	 	Route::post('/jobroles/{id}' 			, 'JobRolesController@update');

	 	Route::get ('/tasks/estimates'	 		, 'TasksController@indexEstimates');
	 	Route::resource('tasks' 				, 'TasksController');
	 	Route::get ('/mytasks'		 			, 'TasksController@myTasks');
	 	Route::post('/tasks/{id}'	 			, 'TasksController@update');
	 	Route::post('/tasks/{id}/changehours'	, 'TasksController@changeHours');
	 	Route::get ('/tasks/{id}/info'	 		, 'TasksController@showTaskInfo');

	 	Route::resource('users' 				, 'UsersController');
	 	Route::post('/users/{id}'	 			, 'UsersController@update');
	 	Route::post('/users/{id}/bindtotask'	, 'UsersController@bindToTask');
	 	Route::post('/users/{id}/unbindtotask'	, 'UsersController@unbindtotask');

	 	Route::resource('usertimelogs' 			, 'UserTimeLogsController');
	 	Route::post('/usertimelogs/{id}' 		, 'UserTimeLogsController@update');

	 	Route::resource('expences'				, 'ExpencesController');
	 	Route::post('/expences/{id}'			, 'ExpencesController@update');

	 	Route::resource('estimateentries'		, 'EstimateEntriesController');
	 	Route::post('/estimateentries/saveorder', 'EstimateEntriesController@updateOrder');
	 	Route::post('/estimateentries/{id}'		, 'EstimateEntriesController@update');

	 	Route::resource('apps'					, 'NgAppsTableController');
	 	Route::post('/apps/{id}'				, 'NgAppsTableController@update');

	 	Route::resource('branches' 			, 'BranchesController');
	 	Route::post('/branches/{id}' 		, 'BranchesController@update');
});

Route::group(array(
		'before' => 'apiauth|crfs',
		'prefix' => 'api/v1/dashboard',
	), function() {
		Route::get('/chartdata'					, 'DashboardController@chartData');
		Route::get('/projectoverview/{id}'		, 'DashboardController@chartData_ProjectOverView');

		Route::get('/projects'					, 'DashboardController@projects');
		Route::get('/clients'					, 'DashboardController@clients');
		Route::get('/taskroles/{id}'			, 'DashboardController@taskroles');
		Route::get('/estimates'					, 'DashboardController@showAllEstimates');
		
		Route::get('/userhours'					, 'DashboardController@getUserHours');
		Route::get('/getestimates'				, 'DashboardController@v_getestimates');
});




