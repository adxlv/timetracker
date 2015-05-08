<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ClientsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /clients
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

		$get = Input::get();
		$archived = (Isset($get['archived']) && $get['archived']=='true')?true:false;

			if ($archived) {
				$clients = Client::with('projects')->get();
			} else {
				$clients = Client::where('archived_at','0000-00-00 00:00:00')
					->with('projects')
					->get();
			}
		
		return $clients;
	}

	public function indexMyClients()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});
		$user_id = Auth::user()->id;


		// $clients = Client::with([
		// 	'projects' => function($q) use ($user_id)  {
		// 		$q->where('created_by', $user_id);
		// 	}
		// ])->whereHas('projects', function($q) use ($user_id)  {
		// 	$q->where('created_by', $user_id);
		// })->get();
		
		
		$clients = DB::select("SELECT DISTINCT
					clients.id,
					clients.title,
					clients.description,
					clients.web,
					clients.mail,
					clients.color,
					clients.created_at,
					clients.updated_at,
					clients.deleted_at,
					clients.archived_at,
					clients.archived_by,
					task_role_binds.jobrole_id,
					role_user_binds.user_id
				FROM
					clients
				LEFT JOIN projects ON projects.client_id = clients.id
				LEFT JOIN tasks ON tasks.project_id = projects.id
				LEFT JOIN task_role_binds ON task_role_binds.task_id = tasks.id
				LEFT JOIN role_user_binds ON role_user_binds.taskrolebinds_id = task_role_binds.id
				WHERE
					task_role_binds.jobrole_id = 1 
					AND clients.archived_at = ?
					AND role_user_binds.user_id=? ",
		array(
			'0000-00-00 00:00:00',
			$user_id,
		));
		

		return $clients;
	}


	/**
	 * Show the form for creating a new resource.
	 * GET /clients/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /clients
	 *
	 * @return Response
	 */
	public function store()
	{
		$client = new Client;

		$client->title           = Input::get('title');
		$client->description     = Input::get('description');
		$client->web             = Input::get('web');
		$client->mail            = Input::get('mail');

		$client->bbox_nr         = Input::get('bbox_nr');
		$client->name            = Input::get('name');
		
		$client->legal_street    = Input::get('legal_street');
		$client->legal_city      = Input::get('legal_city');
		$client->legal_country   = Input::get('legal_country');
		$client->legal_postal    = Input::get('legal_postal');

		$client->reg_nr          = Input::get('reg_nr');

		$client->bank            = Input::get('bank');
		$client->bank_nr         = Input::get('bank_nr');
		$client->bank_code       = Input::get('bank_code');

		$client->contact_name    = Input::get('contact_name');
		$client->contact_details = Input::get('contact_details');

		$client->color           = Input::get('color');

		$client->save();
		return $client;
	}

	/**
	 * Display the specified resource.
	 * GET /clients/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
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

	/**
	 * Display the specified resource.
	 * GET /clients/{id}/projects
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showProjects($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$client = Client::findOrFail($id);


		return $client->projects;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /clients/{id}/edit
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
	 * PUT /clients/{id}
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
		
		$client = Client::findOrFail($id);

		$client->title           = Input::get('title');
		$client->description     = Input::get('description');
		$client->web             = Input::get('web');
		$client->mail            = Input::get('mail');

		$client->bbox_nr         = Input::get('bbox_nr');
		$client->name            = Input::get('name');
		
		$client->legal_street    = Input::get('legal_street');
		$client->legal_city      = Input::get('legal_city');
		$client->legal_country   = Input::get('legal_country');
		$client->legal_postal    = Input::get('legal_postal');

		$client->reg_nr          = Input::get('reg_nr');

		$client->bank            = Input::get('bank');
		$client->bank_nr         = Input::get('bank_nr');
		$client->bank_code       = Input::get('bank_code');

		$client->contact_name    = Input::get('contact_name');
		$client->contact_details = Input::get('contact_details');

		$client->color           = Input::get('color');


		$archive = Input::get('archive');
		if ( Isset($archive) ) {
			// var_dump($archive); exit;
			if ($archive==false) {
				$client->archived_at  = '0000-00-00 00:00:00';
				$client->archived_by  = null;
			} else {
				$client->archived_at  = new DateTime();
				$client->archived_by  = Auth::user()->id;
			}
		};

		$client->save();
		return;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /clients/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
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