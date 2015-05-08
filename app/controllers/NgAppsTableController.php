<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class NgAppsTableController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /apps
	 *
	 * @return Response
	 */
	public function index()
	{
		$apps = NgAppTable::get();;
		return $apps;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /apps/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /apps
	 *
	 * @return Response
	 */
	public function store()
	{
		$app = new NgAppTable;
		$app->app_name = Input::get('app_name');
		$app->data = Input::get('data');

		$app->save();

		return $app;
	}

	/**
	 * Display the specified resource.
	 * GET /apps/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($appname)
	{
		$app = NgAppTable::where('app_name','=',strtolower($appname))->first();
		return $app;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /apps/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

	}

	/**
	 * Update the specified resource in storage.
	 * PUT /apps/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});
		$app = NgAppTable::findOrFail(Input::get('id'));

		$app->app_name = Input::get('app_name');
		$app->data = Input::get('data');
		$app->save();

		return $app;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /apps/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}