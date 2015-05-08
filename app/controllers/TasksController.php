<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TasksController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tasks
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

		$tasks = Task::with('taskrolebinds.users','taskrolebinds.jobrole')->get();
		// $tasks = Task::all();

		return $tasks;
	}

	//GET api/v1/tasks/estimates
	public function indexEstimates()
	{
		App::error(function(ModelNotFoundException $e) {
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$get = Input::get();
		if (isset($get['s_date']) && isset($get['e_date'])) {
			$s_date = new DateTime($get['s_date']);
			$e_date = new DateTime($get['e_date']);

			// return array($s_date->format('Y-m-d H:i:s'), $e_date);

			$projects = Project::with( array(
				'estimates' => function($query) use ($s_date,$e_date) { 
					$query->where('bound_to_estimate_id',null)
						  ->where('created_at', '>=', $s_date)
						  ->where('created_at', '<=', $e_date); 
					// $query->whereBetween('created_at', array($s_date, $e_date));
				},
				'estimates.entries' => function($query) {}
			))->get();
		} else {
			$projects = Project::with( array(
				'estimates' => function($query){ 
					// $query->where('bound_to_estimate_id',null);
					// $query->whereBetween('created_at', array($s_date, $e_date));
				},
				'estimates.entries' => function($query) {}
			))->get();
		}		

		foreach ($projects as $project) {
			$project->sold_m = 0;
			$roles = array(
				'1' => 0,
				'2' => 0,
				'3' => 0,
				'4' => 0,
				'5' => 0,
				'6' => 0,
				'total' => 0,
			);

			if (isSet($project->estimates)) {
				foreach ($project->estimates as $estimate) {
					if (isSet($estimate->entries)) foreach ($estimate->entries as $entry) {

						if (isSet($entry->hours)) foreach ($entry->hours as $key => $hour) {

							if ( isSet($estimate->involved_roles->id[$key]) ) {
								$roles[$estimate->involved_roles->id[$key]] += $hour*60;
							}
							$roles['total'] += $hour*60;
						}

					}
					$project->sold_m = $roles;
				}
			} else {
				$project = null;
			}

		}

		return $projects;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tasks/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tasks
	 *
	 * @return Response
	 */
	public function store()
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$task = new Task;

		$task->title = Input::get('title');
		$task->estimate_id = Input::get('estimate_id');
		$task->project_id = Input::get('project_id');
		$task->save();
		// print_r(); exit;

		$task->jobroles()->sync(Input::get('jobroles'));


		$task = Task::with('taskrolebinds.users','taskrolebinds.jobrole')->findOrFail($task->id);		
		return $task;
	}

	/**
	 * Display the specified resource.
	 * GET /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$task = Task::with('taskrolebinds.users','taskrolebinds.jobrole')->findOrFail($id);
		// User::with('taskroles.jobrole','taskroles.task')->findOrFail($id);
		
		foreach ($task->taskrolebinds as $rolebind) {
			foreach ($rolebind->users as $user) {
				$user->timelogs = $user->timelogs()->where('task_role_bind_id','=',$rolebind->id)->get();
			}
			$rolebind->users_empty = sizeof($rolebind->users)==0;
			// $rolebind->users()->timelogs(); //->where('task_role_bind_id',$rolebind->id);
		}


		return $task;
	}

	/**
	 * Display the specified resource.
	 * GET /tasks/{id}/info
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function showTaskInfo($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$task = Task::with('estimate.project.client')->findOrFail($id);
		// User::with('taskroles.jobrole','taskroles.task')->findOrFail($id);
		return $task;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /tasks/{id}/edit
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
	 * POST /tasks/{id}
	 * PUT /tasks/{id}
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
		
		$task = Task::findOrFail($id);

		$get = [
			'title' => Input::get('title'),
			'done'  => Input::get('done'),
			'estimate_id'  => Input::get('estimate_id'),
		];

		foreach ($get as $key => $value) {
			if (!is_null($value)) $task[$key]=$value;
		}

		$task->save();

		return $task;
	}
	/**
	 * Update the specified resource in storage.
	 * POST /tasks/{id}/changehours
	 * PUT /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function changeHours($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$taskrole = TaskRoleBind::findOrFail(Input::get('taskroleid'));
		$taskrole->hours = Input::get('hours');
		$taskrole->save();
		// return $task;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tasks/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$task = Task::findOrFail($id);
		$task->delete();
	}

}
