<?php

namespace Rogue\Http\Controllers;

use Rogue\Models\Group;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Rogue\Http\Transformers\GroupTransformer;

class GroupsController extends ApiController
{
  /**
   * @var Rogue\Http\Transformers\GroupTransformer;
   */
  protected $transformer;

  /**
   * Create a controller instance.
   */
  public function __construct()
  {
    $this->transformer = new GroupTransformer();
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $query = $this->newQuery(Group::class);

    $filters = $request->query('filter');
    $query = $this->filter($query, $filters, Group::$indexes);

    if (isset($filters['name'])) {
      $query->where('name', 'REGEXP', $filters['name']);
    }

    // This endpoint always returns groups in alphabetical order by name. We'll
    // therefore "force" the query string so that we can use it in `getCursor`.
    // @TODO: There must be a more elegant way of doing this...
    $query->orderBy('name', 'asc');

    $request->query->set('orderBy', 'name,asc');

    if ($cursor = Arr::get($request->query('cursor'), 'after')) {
      $query->whereAfterCursor($cursor);
      $this->useCursorPagination = true;
    }

    return $this->paginatedCollection($query, $request);
  }

  /**
   * Display the specified resource.
   *
   * @param  \Rogue\Models\Group  $group
   * @return \Illuminate\Http\Response
   */
  public function show(Group $group)
  {
    return $this->item($group);
  }
}
