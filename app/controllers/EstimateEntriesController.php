<?php
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EstimateEntriesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /estimateentries
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /estimateentries/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /estimateentries
	 *
	 * @return Response
	 */
	public function store()
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$entry = new EstimateEntry;

		$get = Input::get();

		$entry->estimate_id = $get['estimate_id'];
		$entry->title = $get['title'];
		$entry->hours = isset($get['hours']) ? $get['hours'] : $entry->hours;

		$entry->save();

		return $entry;
	}

	/**
	 * Display the specified resource.
	 * GET /estimateentries/{id}
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
	 * GET /estimateentries/{id}/edit
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
	 * POST /estimateentries/saveorder
	 * PUT /estimateentries/saveorder
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateOrder()
	{
		App::error(function(ModelNotFoundException $e)
		{
			App::abort(404);
		});
		
		$objectarray = Input::get('objectarray');
		// rsort($objectarray);
		// print_r($objectarray[0]->id); exit;

		$counter = sizeof($objectarray);
		foreach ($objectarray as $object) {
			DB::update('UPDATE estimate_entries SET sortorder = '.$counter.' WHERE id='.$object['id']);
			$counter--;
		}
		//UPDATE comments SET post_id = 10 WHERE id IN (3,6,23,56)

		// $entry = EstimateEntry::findOrFail($id);

		// $get = Input::get();
		// $entry->title = $get['title'];
		// $entry->hours = isset($get['hours']) ? $get['hours'] : $entry->hours;

		// $entry->save();

		return $objectarray;
	}
	
	/**
	 * Update the specified resource in storage.
	 * POST /estimateentries/{id}
	 * PUT /estimateentries/{id}
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
		
		$entry = EstimateEntry::findOrFail($id);

		$get = Input::get();
		$entry->title = $get['title'];
		$entry->hours = isset($get['hours']) ? $get['hours'] : $entry->hours;
		$entry->sortorder = isset($get['sortorder']) ? $get['sortorder'] : $entry->sortorder;
		$entry->is_header = isset($get['is_header']) ? $get['is_header'] : $entry->is_header;

		$entry->save();

		return $entry;
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /estimateentries/{id}
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
		
		$entry = EstimateEntry::findOrFail($id);
		$entry->delete();
	}

}