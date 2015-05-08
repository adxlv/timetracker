<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProjectsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /projects
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

		if (Isset($get['client_id'])) {
			$c_id = $get['client_id'];

			if ($archived) {
				$projects = Project::where('client_id',$c_id)
					->with('estimates', 'client')->get();
			} else {
				$projects = Project::where('client_id',$c_id)
					->where('archived_at','0000-00-00 00:00:00')
					->with('estimates', 'client')->get();
			}
		} else  {
			$projects = Project::where('archived_at','0000-00-00 00:00:00')->with('estimates', 'client')->get();
		}

		return $projects;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /projects/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /projects
	 *
	 * @return Response
	 */
	public function store()
	{
		$project = new Project;

		$project->title       = Input::get('title');
		$project->client_id   = Input::get('client_id');
		$project->description = Input::get('description');

		$project->created_by  = Auth::user()->id;
		$project->save();
		
		return $project;
	}

	/**
	 * Display the specified resource.
	 * GET /projects/{id}
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

		$project = Project::with('estimates', 'client')->findOrFail($id);

		return $project;
	}

	/**
	 * Display the specified resource.
	 * GET /projects/{id}
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

		// $project = Project::with('estimates')->find($id);

		// $t = array();

		// foreach ($project->estimates as $estimate) {
		// 	$found = Task::with('taskrolebinds.users','taskrolebinds.jobrole')->where('estimate_id','=',$estimate->id)->get()->toArray();
		// 	foreach ($found as $task) {
		// 		$task['estimate_name'] = $estimate->title;
		// 		$t[] = $task;
		// 	}
		// }
		// print_r($projects);
		 
		$tasks = Task::with('taskrolebinds.users','taskrolebinds.jobrole')->where('project_id', '=', $id)->get();

		return $tasks;
	}

	/**
	 * Display the specified resource.
	 * GET /projects/{id}/rougeTasks
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showRougeTasks($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$tasks = Task::with('taskrolebinds.users','taskrolebinds.jobrole')->where('project_id', '=', $id)->get();

		return $tasks;
	}

	/**
	 * Display the specified resource.
	 * GET /projects/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showEstimates($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    App::abort(404);
		});

		$project = Project::with( array(
			'estimates' => function($query) {
				$query->whereNull('bound_to_estimate_id');
			},
			'estimates.tasks.taskrolebinds.users' => function($query) {},
			'estimates.expences' => function($query) {},
		))->find($id);

		foreach ($project->estimates as $estimate) {
			$estimate->bound = Estimate::where('bound_to_estimate_id','=',$estimate->id)->get();
		}

		return $project->estimates;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /projects/{id}/edit
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
	 * PUT /projects/{id}
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
		
		$project = Project::findOrFail($id);

		$project->title       = Input::get('title');
		$project->description = Input::get('description');

		$archive = Input::get('archive');
		if ( Isset($archive) ) {
			// var_dump($archive); exit;
			if ($archive==false) {
				$project->archived_at  = '0000-00-00 00:00:00';
				$project->archived_by  = null;
			} else {
				$project->archived_at  = new DateTime();
				$project->archived_by  = Auth::user()->id;
			}
		};

		$project->save();
		return;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /projects/{id}
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
		
		$projects = Project::findOrFail($id);
		$projects->delete();

		// return true;
	}

}