<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstimatesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /estimates
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

		// $estimates = Estimate::all();
		$estimates = Estimate::with('project')->get();;

		return $estimates;
		// return $estimates->involved_roles;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /estimates/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /estimates
	 *
	 * @return Response
	 */
	public function store()
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$estimate = new Estimate;

		$estimate->project_id 		= Input::get('project_id');
		$estimate->title 			= Input::get('title');
		$estimate->description 		= Input::get('description');
		$estimate->discount 		= Input::get('discount');
		$estimate->involved_roles 	= array(
			'id'=>[1,2,3,4,5,6],
			'salary'=>[45,70,50,50,35,40]
		); //Input::get('involved_roles');
		$estimate->group 			= Input::get('group');
		$estimate->save();

		foreach ($estimate->tasks as $task) {
			$task->jobroles;
		}

		return $estimate;
	}

	/**
	 * Display the specified resource.
	 * GET /estimates/{id}
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

		
		$estimate = Estimate::with([
			'entries' => function($q) {
    			$q->orderBy('sortorder','desc');
    		},
    		'bill_entries' => function($q) {},
    		'expences' => function() {},
    		'branch' => function() {},
    	])->find($id);

		$bound_estimates = Estimate::with([
			'entries' => function($q) {
    			$q->orderBy('sortorder','desc');
    		},
    		'bill_entries' => function($q) {},
    		'expences' => function() {},
    		'branch' => function() {},
    	])->where('bound_to_estimate_id', '=', $id)->get();

		// foreach ($bound_estimates as $key => $this_estimate) {
		// 	$estimate_total = 0;
		// 	foreach ($this_estimate->tasks as $key => $task) {
		// 		foreach ($task->jobroles as $key => $jobrole) {
		// 			$estimate_total += $jobrole->pivot->hours * $jobrole->salary_neto;
		// 		}
		// 	}

		// 	$expences_total = 0;
		// 	foreach ($this_estimate->expences as $key => $exp) {
		// 		$expences_total += $exp->qty * $exp->price;
		// 	}


		// 	$this_estimate->total = array(
		// 		'estimate' => $estimate_total,
		// 		'expences' => $expences_total,
		// 	);
		// }

		// $estimate->bound_estimates = $bound_estimates->toArray();

		return $estimate;
	}

	/**
	 * Display the specified resource.
	 * GET /estimates/{id}/tasks
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showTasks($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$estimate = Estimate::findOrFail($id);
		foreach ($estimate->tasks as $task) {
			$task->jobroles;
		}
		return $estimate->tasks;
	}

	/**
	 * Display the specified resource.
	 * GET /estimates/{id}/jobroles
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showJobRoles($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$estimate = Estimate::findOrFail($id);
		return $estimate->tasks->first()->jobroles;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /estimates/{id}/edit
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
	 * POST /estimates/{id}
	 * PUT /estimates/{id}
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
		
		$estimate = Estimate::findOrFail($id);

		// $estimate->project_id 		= Input::get('project_id');
		// $estimate->title 			= Input::get('title');
		// $estimate->description 		= Input::get('description');
		// $estimate->discount 		= Input::get('discount');
		// $estimate->involved_roles 	= Input::get('involved_roles');

		// $get = [
		// 	'project_id'           => Input::get('project_id'),
		// 	'title'                => Input::get('title'),
		// 	'description'          => Input::get('description'),
		// 	'discount'             => Input::get('discount'),
		// 	'involved_roles'       => Input::get('involved_roles'),
		// 	'bound_to_estimate_id' => Input::get('bound_to_estimate_id'),
		// 	'group'                => Input::get('group'),
		// ];
		
		
		$get = Input::get();

		$estimate->project_id           = isset($get['project_id']) ? $get['project_id'] : $estimate->project_id;
		$estimate->title                = isset($get['title']) ? $get['title'] : $estimate->title;
		$estimate->title                = isset($get['title']) ? $get['title'] : $estimate->title;
		$estimate->description          = isset($get['description']) ? $get['description'] : $estimate->description;
		$estimate->discount             = isset($get['discount']) ? $get['discount'] : $estimate->discount;
		$estimate->involved_roles       = isset($get['involved_roles']) ? $get['involved_roles'] : $estimate->involved_roles;
		$estimate->bound_to_estimate_id = isset($get['bound_to_estimate_id']) ? $get['bound_to_estimate_id'] : $estimate->bound_to_estimate_id;
		$estimate->group                = isset($get['group']) ? $get['group'] : $estimate->group;
		$estimate->total_summ           = isset($get['total_summ']) ? $get['total_summ'] : $estimate->total_summ;
		
		if ($estimate->bound_to_estimate_id==0) {
			$estimate->bound_to_estimate_id = null;
		}

		$estimate->save();

		$estimate->bound = Estimate::where('bound_to_estimate_id','=',$estimate->id)->get();

		foreach ($estimate->tasks as $task) {
			$task->jobroles;
		}

		return $estimate;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /estimates/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$estimate = Estimate::findOrFail($id);
		$estimate->delete();
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