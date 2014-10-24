<?php

class TagController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /tag
	 *
	 * @return Response
	 */
	public function index()
	{
		$tags = Tag::all();
        return View::make('tags.index')
            ->with('tags', $tags);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /tag/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /tag
	 *
	 * @return Response
	 */
	public function store()
	{
		$name = Input::get("name");
        $tag = new Tag();
        $tag->name = $name;
        $tag->save();
        return Redirect::route('tags.index');

    }

	/**
	 * Display the specified resource.
	 * GET /tag/{id}
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
	 * GET /tag/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$tag = Tag::find($id);
        return View::make("tags.partials.edit")
                        ->with("tag", $tag);
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /tag/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
        $tag = Tag::find($id);
		$name = Input::get('name');
        $tag->name = $name;
        $tag->save();
        return Redirect::route("tags.index");

	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /tag/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$tag = Tag::findOrFail($id);
        $problems = $tag->problems()->get();
        DB::table('problem_tag')->where('tag_id', '=', $id)->delete();

        $tag->delete();
	}

}