<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UsersController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /users
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

		// $users = User::all();
		
		// foreach ($users as $key => $user) {
		// 	global $gid;
		// 	$gid = $user->id;

		// 	$get_user = User::with( array(
		// 		'taskroles' => function($query) { 
		// 			# show only unfinished tasks
		// 		},
		// 		'taskroles.jobrole' => function($query){ 
		// 			# do code in here
		// 		},
		// 		'taskroles.task' => function($query){ 
		// 			# do code in here
		// 			$query->where('done', '=', 0); 
		// 		},
		// 		'taskroles.task.project' => function($query){ 
		// 			# do code in here
		// 		},
		// 		'taskroles.usertimelogs' => function($query) { 
		// 			# show only timelogs from this user
		// 			global $gid;
		// 			$query->where('user_id', '=', $gid); 
		// 		}
		// 	))->findOrFail($gid);

		// 	$users[$key] = $get_user->toArray();
		// }

		// return $users;
		
		$users = User::with( array(
			'taskroles' => function($query) { 
				# show only unfinished tasks
				
			},
			'taskroles.jobrole' => function($query){ 
				# do code in here
			},
			'taskroles.task' => function($query){ 
				# do code in here
				$query->where('done', '=', 0); 
			},
			'taskroles.task.project' => function($query){ 
					# do code in here
			},
			'taskroles.task.project.client' => function($query){ 
					# do code in here
			},
			// 'taskroles.usertimelogs' => function($query) { 
			// 	# show only timelogs from this user
			// 	global $gid;
			// 	$query->where('user_id', '=', $gid); 
			// }
		))->get();

		foreach ($users as $user) {
			foreach ($user->taskroles as $taskrole) {
				$taskrole->usertimelogs = $taskrole->usertimelogs()->where('user_id', '=', $user->id)->get();
			}
		}

		return $users;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /users/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /users
	 *
	 * @return Response
	 */
	public function store()
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$user = new User;
		$get = Input::get();

		$user->name       = isset($get['name']) ? $get['name'] : $user->name;
		$user->surname    = isset($get['surname']) ? $get['surname'] : $user->surname;
		$user->email      = isset($get['email']) ? $get['email'] : $user->email;
		$user->login      = isset($get['login']) ? $get['login'] : $user->login;
		$user->pass       = isset($get['password']) ? Hash::make($get['password']) : $user->pass;
		$user->group      = isset($get['group']) ? $get['group'] : $user->group;

		$user->pict = '/store/img/users/default.jpg';//      = isset($get['pict']) ? $get['pict'] : $user->pict;


		$user->save();
		return $user;
	}

	/**
	 * Display the specified resource.
	 * GET /users/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    // App::abort(404);
		});
		global $gid;
		$gid = $id;

		$user = User::with( array(
			'taskroles' => function($query) { 
				# show only unfinished tasks
				$query->whereHas('task', function($query){
					$query->where('done', '=', 0);
				});
			},
			'taskroles.jobrole' => function($query){ 
				# do code in here
			},
			// 'taskroles.task' => function($query){ 
			// 	# do code in here
			// 	// $query->where('done', '=', 0); 
			// 	// $query->whereHas('task', function($query){

			// 	// });
			// },
			'taskroles.task.project' => function($query){ 
					# do code in here
			},
			'taskroles.task.project.client' => function($query){ 
					# do code in here
			},
			'taskroles.usertimelogs' => function($query) { 
				# show only timelogs from this user
				global $gid;
				$query->where('user_id', '=', $gid); 
			}
		))->findOrFail($id);

		return $user;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /users/{id}/edit
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
	 * PUT /users/{id}
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
		
		$user = User::findOrFail($id);
		$get = Input::get();

		$user->name       = isset($get['name']) ? $get['name'] : $user->name;
		$user->surname    = isset($get['surname']) ? $get['surname'] : $user->surname;
		$user->email      = isset($get['email']) ? $get['email'] : $user->email;
		$user->login      = isset($get['login']) ? $get['login'] : $user->login;
		$user->pass       = isset($get['password']) ? Hash::make($get['password']) : $user->pass;
		$user->group      = isset($get['group']) ? $get['group'] : $user->group;

		$user->pict       = isset($get['pict']) ? $get['pict'] : $user->pict;


		$user->save();
		return $user;
	}

	/**
	 * Bind User to Tasks
	 * PUT /users/{id}/bindtotask
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function bindToTask($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$user = User::findOrFail($id);
		// $taskrole = TaskRole::findOrFail(Input::get('taskrole'));

		$user->taskroles()->attach(Input::get('taskrole'));
		// $user->title       = Input::get('title');
		// $user->description = Input::get('description');

		// $user->save();
		return $user;
	}

	/**
	 * UnBind User from Tasks
	 * PUT /users/{id}/unbindtotask
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function unbindToTask($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$user = User::with('taskroles')->findOrFail($id);
		$user->taskroles()->detach(Input::get('taskrole'));

		return $user;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /users/{id}
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
		
		$users = user::findOrFail($id);
		$users->delete();

		// return true;
	}

}