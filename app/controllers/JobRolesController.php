<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class JobRolesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /jobroles
	 *
	 * @return Response
	 */
	public function index()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$jobroles = JobRole::all();

		return $jobroles;
		// return Response::json( $response );
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /jobroles/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /jobroles
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 * GET /jobroles/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /jobroles/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /jobroles/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /jobroles/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}


/*
class ClientsController extends \BaseController {

	public function index()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$clients = Client::all();

		// print_r($clients);

		$response = array(
            'isPost'  => Request::isMethod('post'),
            'status'  => 'success',
            'clients' => $clients->toArray(),
        );

		return $clients;
		// return Response::json( $response );
	}

	
	public function create()
	{
		//
	}

	
	public function store()
	{
		$client = new Client;

		$client->title       = Input::get('title');
		$client->description = Input::get('description');
		$client->web         = Input::get('web');
		$client->mail        = Input::get('mail');

		$client->save();
		return;
	}

	
	public function show($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$clients = Client::findOrFail($id);

		// print_r($clients);

		return $clients;
	}


	public function showProjects($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$client = Client::findOrFail($id);

		// print_r($client-projects());

		// return 'gut';
		return $client->projects;
	}

	
	public function edit($id)
	{
		//
	}

	public function update($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$client = Client::findOrFail($id);

		$client->title       = Input::get('title');
		$client->description = Input::get('description');
		$client->web         = Input::get('web');
		$client->mail        = Input::get('mail');

		$client->save();
		return;
	}

	public function destroy($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});
		
		$client = Client::findOrFail($id);
		$client->delete();

		// return true;
	}

}