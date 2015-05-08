<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExpencesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /expences
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /expences/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /expences
	 *
	 * @return Response
	 */
	public function store()
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$expence = new Expence;

		$expence->title = Input::get('title');
		$expence->estimate_id = Input::get('estimate_id');
		$expence->save();

		$expence = Expence::findOrFail($expence->id);
		
		return $expence;
	}

	/**
	 * Display the specified resource.
	 * GET /expences/{id}
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
	 * GET /expences/{id}/edit
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
	 * POST /expences/{id}
	 * PUT /expences/{id}
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
		$expence = new Expence;
		
		$expence = Expence::findOrFail($id);

		if (Input::get('title')) {
			$expence->title = Input::get('title');
		}
		if (Input::get('units')) {
			$expence->units = Input::get('units');
		}
		if (Input::get('qty')) {
			$expence->qty = Input::get('qty');
		}
		if (Input::get('price')) {
			$expence->price = Input::get('price');
		}

		$expence->save();
		return $expence;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /expences/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$expence = Expence::findOrFail($id);
		$expence->delete();
	}

}