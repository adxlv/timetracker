<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DashboardController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /projects
	 *
	 * @return Response
	 */
	public function projects()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$projects = Project::with( array(
			'client' => function($query) {},
			'estimates.tasks' => function($query) {},
			'estimates.tasks.taskrolebinds' => function($query) { 
				# show only unfinished tasks
				# global $gid;
				$query->where('hours', '!=', 'null'); 
			},
			'estimates.tasks.taskrolebinds.jobrole' => function($query) {},
			'estimates.tasks.taskrolebinds.task' => function($query) {},

			'estimates.tasks.taskrolebinds.users' => function($query) {},
			'estimates.tasks.taskrolebinds.users.timelogs' => function($query) {},
			// 'estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		))->get();



		foreach ($projects as $project) {
			$project->done_m = 0;
			$project->sold_m = 0;
			$rougetasks = Task::with( array(
				'taskrolebinds.task'           => function($query) {},
				'taskrolebinds.jobrole'        => function($query) {},
				'taskrolebinds.users.timelogs' => function($query) {},
			))->where('project_id', '=', $project->id)->whereNull('estimate_id')->get();

			foreach ($project->estimates as $estimate) {
				$estimate->done_m = 0;
				$estimate->sold_m = 0;

				foreach ($estimate->tasks as $task) {
					$task->done_m = 0;
					$task->sold_m = 0;

					foreach ($task->taskrolebinds as $taskrole) {
						$taskrole->done_m = 0;
						$taskrole->sold_m = 0;

						foreach ($taskrole->users as $user) {
							$user->done_m = 0;
							$user->sold_m = 0;
							
							foreach ($user->timelogs as $timelog) {
								if ($timelog->minutes && $timelog->task_role_bind_id == $taskrole->id) $user->done_m += $timelog->minutes;
							}

							$taskrole->done_m += $user->done_m;
							$taskrole->sold_m  = $taskrole->hours*60;
						}
						$taskrole->users_empty = count($taskrole->users) == 0;
						
						$task->done_m += $taskrole->done_m;
						$task->sold_m += $taskrole->sold_m;
					}
					$task->taskrolebinds_empty = count($task->taskrolebinds) == 0;
					
					$estimate->done_m += $task->done_m;
					$estimate->sold_m += $task->sold_m;
				}

				$rougetasks_done_m = 0;
				foreach ($rougetasks as $rougetask) {
					$rougetask->done_m = 0;

					foreach ($rougetask->taskrolebinds as $taskrole) {
						$taskrole->done_m = 0;

						foreach ($taskrole->users as $user) {
							$user->done_m = 0;
							
							foreach ($user->timelogs as $timelog) {
								if ($timelog->minutes && $timelog->task_role_bind_id == $taskrole->id) $user->done_m += $timelog->minutes;
							}

							$taskrole->done_m += $user->done_m;
						}
						
						$rougetask->done_m += $taskrole->done_m;
					}
					
					$rougetasks_done_m += $rougetask->done_m;
				}

				$estimate->tasks_empty = count($estimate->tasks) == 0;

				$project->done_m += $estimate->done_m + $rougetasks_done_m;
				$project->sold_m += $estimate->sold_m;

				$project->rougetasks = $rougetasks->toArray();
			}
			$project->estimates_empty = count($project->estimates) == 0;
			$project->rougetasks_empty = count($rougetasks) == 0;
		}

		return $projects;
	}
	public function clients() {
		$clients = Client::with( array(
			'projects.estimates' => function($query) {},
			'projects.estimates.entries' => function($query) {},
			'projects.estimates.expences' => function($query) {},
			
			'projects.tasks.taskrolebinds' => function($query) { 
				# show only unfinished tasks
				# global $gid;
				// $query->where('hours', '!=', 'null'); 
			},
			'projects.tasks.taskrolebinds.jobrole' => function($query) {},
			'projects.tasks.taskrolebinds.task' => function($query) {},

			'projects.tasks.taskrolebinds.users' => function($query) {},
			'projects.tasks.taskrolebinds.users.timelogs' => function($query) {},
			// 'estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		))->get();

		$done_m = [];
		$esti_m = [];

		foreach ($clients as $client) {
			$client->sold_m = 0;
			$client->done_m = 0;

			foreach ($client->projects as $project) {
				$project = $this::sold_done_calculate($project);
				$client->sold_m += $project->sold_m;
				$client->done_m += $project->done_m;
			}


			$done_m[] = array(
				$client->title, 
				$client->done_m, 
			);

			$esti_m[] = array(
				$client->title, 
				$client->sold_m, 
			);
		}

		return $clients;
	}

	public function taskroles($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		// $project = Project::with( array(
		// 	'estimates.tasks' => function($query) {},
		// 	'estimates.tasks.taskrolebinds' => function($query) { 
		// 		# show only unfinished tasks
		// 		# global $gid;
		// 		$query->where('hours', '!=', 'null'); 
		// 	},
		// 	'estimates.tasks.taskrolebinds.jobrole' => function($query) {},
		// 	'estimates.tasks.taskrolebinds.task' => function($query) {},

		// 	'estimates.tasks.taskrolebinds.users' => function($query) {},
		// 	'estimates.tasks.taskrolebinds.users.timelogs' => function($query) {},
		// 	// 'estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		// ))->find($id);

		$project = Project::with( array(
			'estimates' => function($query) {},
			'estimates.entries' => function($query) {},
			'estimates.expences' => function($query) {},
			
			'tasks.taskrolebinds' => function($query) { 
				# show only unfinished tasks
				# global $gid;
				// $query->where('hours', '!=', 'null'); 
			},
			'tasks.taskrolebinds.jobrole' => function($query) {},
			'tasks.taskrolebinds.task' => function($query) {},

			'tasks.taskrolebinds.users' => function($query) {},
			'tasks.taskrolebinds.users.timelogs' => function($query) {},
			// 'estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		))->find($id);

		// $taskroles = null;
		// $tasks = null;

		// $rougetasks = Task::with( array(
		// 	'taskrolebinds.task'           => function($query) {},
		// 	'taskrolebinds.jobrole'        => function($query) {},
		// 	'taskrolebinds.users.timelogs' => function($query) {},
		// ))->where('project_id', '=', $project->id)->whereNull('estimate_id')->get();

			// foreach ($project->estimates as $estimate) {
			// 	$estimate->done_m = 0;
			// 	$estimate->sold_m = 0;

			// 	foreach ($estimate->tasks as $task) {
			// 		$task->done_m = 0;
			// 		$task->sold_m = 0;

			// 		foreach ($task->taskrolebinds as $taskrole) {
			// 			$taskrole->done_m = 0;
			// 			$taskrole->sold_m = 0;

			// 			foreach ($taskrole->users as $user) {
			// 				$user->done_m = 0;
			// 				$user->sold_m = 0;
							
			// 				foreach ($user->timelogs as $timelog) {
			// 					if ($timelog->minutes && $timelog->task_role_bind_id == $taskrole->id) $user->done_m += $timelog->minutes;
			// 				}

			// 				$taskrole->done_m += $user->done_m;
			// 				$taskrole->sold_m  = $taskrole->hours*60;
			// 			}
			// 			$taskrole->users_empty = count($taskrole->users) == 0;
						
			// 			$task->done_m += $taskrole->done_m;
			// 			$task->sold_m += $taskrole->sold_m;
			// 		}
			// 		$task->taskrolebinds_empty = count($task->taskrolebinds) == 0;
					
			// 		$estimate->done_m += $task->done_m;
			// 		$estimate->sold_m += $task->sold_m;
			// 	}

			// 	$estimate->tasks_empty = count($estimate->tasks) == 0;

			// 	$project->done_m += $estimate->done_m;
			// 	$project->sold_m += $estimate->sold_m;

			// 	$project->rougetasks = $rougetasks->toArray();
			// }
			// $project->estimates_empty  = count($project->estimates) == 0;
			// $project->rougetasks_empty = count($rougetasks) == 0;
		
		$data = $this::sold_done_calculate($project);

		return $data;
	}

	public function showAllEstimates() 
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$estimates = Estimate::with( array(
			'project' => function($query) {},
			'expences' => function($query) {},
			'tasks' => function($query) {},
			'tasks.taskrolebinds' => function($query) { 
				$query->where('hours', '!=', 'null'); 
			},
			'tasks.taskrolebinds.jobrole' => function($query) {},
			'tasks.taskrolebinds.task' => function($query) {},
			'tasks.taskrolebinds.users' => function($query) {},
			'tasks.taskrolebinds.users.timelogs' => function($query) {},
		))->get();


		foreach ($estimates as $estimate) {
			$estimate->done_m = 0;
			$estimate->sold_m = 0;
			$estimate->cost_hours = 0;
			$estimate->cost_additional = 0;

			# TASKS #
			foreach ($estimate->tasks as $task) {
				$task->done_m = 0;
				$task->sold_m = 0;
				$task->cost_hours = 0;

				foreach ($task->taskrolebinds as $taskrole) {
					$taskrole->done_m = 0;
					$taskrole->sold_m = $taskrole->hours*60;
					$taskrole->cost_hours = $taskrole->jobrole->salary_neto * ($taskrole->hours);

					foreach ($taskrole->users as $user) {
						$user->done_m = 0;
						// $user->sold_m = $taskrole->hours*60;
						
						foreach ($user->timelogs as $timelog) {
							if ($timelog->minutes && $timelog->task_role_bind_id == $taskrole->id) $user->done_m += $timelog->minutes;
						}

						$taskrole->done_m += $user->done_m;
						// $taskrole->sold_m  = $taskrole->hours*60;
					}
					$taskrole->users_empty = count($taskrole->users) == 0;
					
					$task->done_m += $taskrole->done_m;
					$task->sold_m += $taskrole->sold_m;
					$task->cost_hours += $taskrole->cost_hours;
				}
				$task->taskrolebinds_empty = count($task->taskrolebinds) == 0;
				
				$estimate->done_m += $task->done_m;
				$estimate->sold_m += $task->sold_m;
				$estimate->cost_hours += $task->cost_hours;
			}
			$estimate->tasks_empty = count($estimate->tasks) == 0;

			# ADDITIONAL EXPENCES #
			foreach ($estimate->expences as $expence) {
				$expence->cost_additional = $expence->qty * $expence->price;
				
				$estimate->cost_additional += $expence->cost_additional;
			}
			$estimate->expences_empty = count($estimate->expences) == 0;
		}
		// $project->estimates_empty = count($project->estimates) == 0;

		return $estimates;
	}

	public function chartData()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$clients = Client::with( array(
			'projects.estimates.tasks.taskrolebinds' => function($query) { 
				# show only unfinished tasks
				# global $gid;
				$query->where('hours', '!=', 'null'); 
			},
			'projects.estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		))->get();

		$clients = Client::with( array(
			'projects.estimates' => function($query) {},
			'projects.estimates.entries' => function($query) {},
			'projects.estimates.expences' => function($query) {},
			
			'projects.tasks.taskrolebinds' => function($query) { 
				# show only unfinished tasks
				# global $gid;
				// $query->where('hours', '!=', 'null'); 
			},
			'projects.tasks.taskrolebinds.jobrole' => function($query) {},
			'projects.tasks.taskrolebinds.task' => function($query) {},

			'projects.tasks.taskrolebinds.users' => function($query) {},
			'projects.tasks.taskrolebinds.users.timelogs' => function($query) {},
			// 'estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		))->get();


		$done_m = [];
		$esti_m = [];

		foreach ($clients as $client) {
			$client->sold_m = 0;
			$client->done_m = 0;

			foreach ($client->projects as $project) {
				$project = $this::sold_done_calculate($project);
				$client->sold_m += $project->sold_m;
				$client->done_m += $project->done_m;
			}


			$done_m[] = array(
				$client->title, 
				$client->done_m, 
			);

			$esti_m[] = array(
				$client->title, 
				$client->sold_m, 
			);
		}
		// return $clients;

		return array(
			'done_pie'     => $done_m,
			'estimate_pie' => $esti_m,
		);
	}

	public function chartData_ProjectOverView($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$project = Project::with( array(
			'estimates' => function($query) {
				$query->whereNull('bound_to_estimate_id');
			},
			'estimates.entries' => function($query) {},
			'estimates.expences' => function($query) {},
			
			'tasks.taskrolebinds' => function($query) { 
				# show only unfinished tasks
				# global $gid;
				// $query->where('hours', '!=', 'null'); 
			},
			'tasks.taskrolebinds.jobrole' => function($query) {},
			'tasks.taskrolebinds.task' => function($query) {},

			'tasks.taskrolebinds.users' => function($query) {},
			'tasks.taskrolebinds.users.timelogs' => function($query) {},
			// 'estimates.tasks.taskrolebinds.usertimelogs' => function($query) {},
		))->find($id);

		$data = $this::sold_done_calculate($project);

		$timedata = DB::select('SELECT
				tasks.project_id,
				task_role_binds.id,
				tasks.title,
				task_role_binds.jobrole_id,
				users.`name` AS user_name,
				user_time_logs.time AS time,
				Sum(user_time_logs.minutes) AS minutes,
				users.id AS user_id
			FROM
				tasks
			LEFT JOIN task_role_binds ON task_role_binds.task_id = tasks.id
			LEFT JOIN user_time_logs ON user_time_logs.task_role_bind_id = task_role_binds.id
			INNER JOIN users ON user_time_logs.user_id = users.id
			WHERE
				tasks.project_id = ?
			GROUP BY
				user_time_logs.time,
				task_role_binds.jobrole_id,
				users.id',
			array(
					$id
			));

		foreach ($timedata as $key => $value) {
			$date = new DateTime($value->time);
			$value->time = $date->format(DateTime::ATOM);
		};

		// $done_data = DB::select('SELECT
		// 		tasks.project_id,
		// 		task_role_binds.id,
		// 		task_role_binds.jobrole_id,
		// 		users.`name` AS user_name,
		// 		Sum(user_time_logs.minutes) AS minutes,
		// 		users.id AS user_id
		// 	FROM
		// 		tasks
		// 	LEFT JOIN task_role_binds ON task_role_binds.task_id = tasks.id
		// 	LEFT JOIN user_time_logs ON user_time_logs.task_role_bind_id = task_role_binds.id
		// 	INNER JOIN users ON user_time_logs.user_id = users.id
		// 	WHERE
		// 		tasks.project_id = ?
		// 	GROUP BY
		// 		task_role_binds.jobrole_id',
		// 	array(
		// 			$id
		// 	));

		// $sold_data = new stdClass();
		$sold_data = array();
		$sold_data_raw = DB::select('SELECT
				estimates.project_id,
				estimate_entries.id,
				estimate_entries.estimate_id,
				estimate_entries.hours,
				estimates.involved_roles
			FROM
				estimates
			INNER JOIN estimate_entries ON estimate_entries.estimate_id = estimates.id
			WHERE
				estimates.project_id = ?
			AND estimates.bound_to_estimate_id IS NULL
			AND estimate_entries.is_header = 0',
			array(
					$id
			));
		foreach ($sold_data_raw as $estimate) {
			$estimate->involved_roles = json_decode($estimate->involved_roles);
			$estimate->hours = json_decode($estimate->hours);

			foreach ($estimate->involved_roles->id as $key => $id) {
				if (isset($sold_data[$id])) {
					$sold_data[$id]['minutes'] += $estimate->hours[$key]*60;
					$sold_data[$id]['summ'] += $estimate->hours[$key]*$estimate->involved_roles->salary[$key];
				} else {
					$sold_data[$id] = array(
						'id' => $id,
						'minutes' => $estimate->hours[$key]*60,
						'summ' => $estimate->hours[$key]*$estimate->involved_roles->salary[$key],
					);
				}
			}
		}

		# Forming values
		$project_overview_pie_perc_Done = ($data->sold_m==0) ? 100 : ($data->done_m/$data->sold_m)*100;
		$project_overview_pie_perc_Sold = ($data->sold_m==0) ? 0 : 100 - ($data->done_m/$data->sold_m);

		return [
			'project_overview_pie' => array(
				['Done', $data->done_m],
				['Sold', $data->sold_m],
			),
			'project_overview_raw' => $timedata,
			'data' => $data,
			'project_overview_time' => array(
				'sold' => $sold_data,
			),
		];
	}
	

	### USING SQL VIEWS ###
	public function v_getestimates() {
		$ret = DB::table('v_all_estimates')->get();
		$total_h = 0;
		foreach ($ret as $value) {
			$total_h+=json_decode($value->hours)[0];
		}
		return '['.$total_h.']';

	}
	

	public function getUserHours() {
		// return DB::table('v_total_hours')->get();

		$get = Input::get();
		if (isset($get['s_date']) && isset($get['e_date'])) {
			$s_date = new DateTime($get['s_date']);
			$e_date = new DateTime($get['e_date']);

			return DB::select('SELECT projects.id AS projects_id, tasks.id AS tasks_id, projects.client_id AS clients_id, task_role_binds.jobrole_id AS jobrole_id, Sum(user_time_logs.minutes) AS minutes, user_time_logs.user_id AS user_id, task_role_binds.id AS taskrolebind_id, user_time_logs.time from (`user_time_logs` left join (`task_role_binds` left join (`tasks` left join `projects` on((`tasks`.`project_id` = `projects`.`id`))) on((`task_role_binds`.`task_id` = `tasks`.`id`))) on((`user_time_logs`.`task_role_bind_id` = `task_role_binds`.`id`))) WHERE user_time_logs.time >= ? AND user_time_logs.time <= ? group by `tasks`.`id`,`task_role_binds`.`jobrole_id`,`user_time_logs`.`user_id` ',
				array(
					$s_date,
					$e_date
				));
		} else {
			return DB::select('SELECT projects.id AS projects_id, tasks.id AS tasks_id, projects.client_id AS clients_id, task_role_binds.jobrole_id AS jobrole_id, Sum(user_time_logs.minutes) AS minutes, user_time_logs.user_id AS user_id, task_role_binds.id AS taskrolebind_id, user_time_logs.time from (`user_time_logs` left join (`task_role_binds` left join (`tasks` left join `projects` on((`tasks`.`project_id` = `projects`.`id`))) on((`task_role_binds`.`task_id` = `tasks`.`id`))) on((`user_time_logs`.`task_role_bind_id` = `task_role_binds`.`id`))) group by `tasks`.`id`,`task_role_binds`.`jobrole_id`,`user_time_logs`.`user_id` ');
		}
		App::abort(404);

	}
}