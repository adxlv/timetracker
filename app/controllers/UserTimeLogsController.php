<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserTimeLogsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /usertimelogs
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

		$logs = UserTimeLog::with('user','task')->get();
		return $logs;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /usertimelogs/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /usertimelogs
	 *
	 * @return Response
	 */
	public function store()
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$log = new UserTimeLog;

		$log->user_id           = Input::get('user_id');
		$log->task_role_bind_id = Input::get('taskrole_id');
		$log->minutes           = Input::get('minutes');
		$log->time              = Input::get('time');
		$log->comment           = Input::get('comment');

		$log->save();

		return $log;
	}

	/**
	 * Display the specified resource.
	 * GET /usertimelogs/{id}
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
	 * GET /usertimelogs/{id}/edit
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
	 * PUT /usertimelogs/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		App::error(function(ModelNotFoundException $e)
		{
		    return Response::json( array(
		    	'status'=>'error',
		    	'msg'=>'page bit not found',
		    ));
		});

		$log = UserTimeLog::findOrFail($id);

		$log->minutes = Input::get('minutes');
		$log->comment = Input::get('comment');

		$log->save();

		return $log;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /usertimelogs/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}