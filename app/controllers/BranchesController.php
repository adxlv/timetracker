<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BranchesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /branches
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
		$branches = Branches::get();

		return $branches;
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /branches/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /branches
	 *
	 * @return Response
	 */
	public function store()
	{
		$branches = new Branches;

		$branches->title           = Input::get('title');
		$branches->description     = Input::get('description');
		$branches->type            = Input::get('type');
		$branches->chairman        = Input::get('chairman');
		$branches->image_url       = Input::get('image_url');

		$branches->name            = Input::get('name');
		
		$branches->legal_street    = Input::get('legal_street');
		$branches->legal_city      = Input::get('legal_city');
		$branches->legal_country   = Input::get('legal_country');
		$branches->legal_postal    = Input::get('legal_postal');

		$branches->reg_nr          = Input::get('reg_nr');

		$branches->bank            = Input::get('bank');
		$branches->bank_nr         = Input::get('bank_nr');
		$branches->bank_code       = Input::get('bank_code');

		$branches->contact_name    = Input::get('contact_name');
		$branches->contact_details = Input::get('contact_details');


		$branches->save();
		return $branches;
	}

	/**
	 * Display the specified resource.
	 * GET /branches/{id}
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

		$branches = Branches::findOrFail($id);
		return $branches;
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /branches/{id}/edit
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
	 * PUT /branches/{id}
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

		$branches = Branches::findOrFail($id);

		$branches->title           = Input::get('title');
		$branches->description     = Input::get('description');
		$branches->type            = Input::get('type');
		$branches->chairman        = Input::get('chairman');
		$branches->image_url       = Input::get('image_url');

		$branches->name            = Input::get('name');
		
		$branches->legal_street    = Input::get('legal_street');
		$branches->legal_city      = Input::get('legal_city');
		$branches->legal_country   = Input::get('legal_country');
		$branches->legal_postal    = Input::get('legal_postal');

		$branches->reg_nr          = Input::get('reg_nr');

		$branches->bank            = Input::get('bank');
		$branches->bank_nr         = Input::get('bank_nr');
		$branches->bank_code       = Input::get('bank_code');

		$branches->contact_name    = Input::get('contact_name');
		$branches->contact_details = Input::get('contact_details');


		$branches->save();
		return $branches;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /branches/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}