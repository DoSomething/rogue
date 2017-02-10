<?php

namespace Rogue\Http\Controllers\Api;

use Rogue\Models\Signup;
use App\Http\Controllers\Controller;
use Rogue\Http\Transformers\ActivityTransformer;
use Illuminate\Http\Request;

class ActivityController extends ApiController
{
    /**
     * @var \League\Fractal\TransformerAbstract;
     */
    protected $transformer;

    /**
     * Create a controller instance.
     *
     */
    public function __construct()
    {

    }

    /**
     * Returns signup activity.
     * GET /activity
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Create an empty Signup query, which we can either filter (below)
        // or paginate to retrieve all signup records.
        $query = $this->newQuery(Signup::class);

        $filters = $request->all();
        // $filters = $request->query('filter');
        dd(Signup::$indexes);
        // $filters = $request->query('filter');
        $query = $this->filter($query, $filters, Signup::$indexes);


        if (isset($filters['count'])) {
            $count = $filters['count'];
            unset($filters['count']);

            $signups = Signup::where($filters)->take($count)->get();
        } else {
            $signups = Signup::where($filters)->paginate(15);
        }

                            // ->paginate(3);

                            // dd($signups);
        // with paginate, it comes with the parameter ?page= already set up
        // $signups = Signup::paginate(15);

        $this->transformer = new ActivityTransformer;

        return $this->collection($signups, 200);
    }

    /**
     * Set the filters based on request URL parameters
     * This is taken from https://github.com/DoSomething/phoenix/blob/dda57aec205c5455df95d843ed548246a81ab740/lib/modules/dosomething/dosomething_helpers/dosomething_helpers.module#L321
     *
     * @param array $parameters
     * @return array
     */
    public function setFilters($parameters)
    {
        $filters = [
            'campaign_id' => isset($parameters['campaigns']) ? $this->formatData($parameters['campaigns']) : "",
            'campaign_run_id' => isset($parameters['campaign_runs']) ? $this->formatData($parameters['campaign_runs']) : "",
            'count' => isset($parameters['count']) ? $this->formatData($parameters['count']) : "",
        ];

        foreach ($filters as $key => $value) {
            if ($value === "") {
                unset($filters[$key]);
            }
        }

        return $filters;
    }

    /**
     * Format a string of comma separated data items into an array, or
     * if a single item, return it without formatting.
     * This is taken from https://github.com/DoSomething/phoenix/blob/dda57aec205c5455df95d843ed548246a81ab740/lib/modules/dosomething/dosomething_helpers/dosomething_helpers.module#L321
     *
     * @param string $data Single or multiple comma separated data items.
     * @return string|array
     */
    function formatData($data) {
      $array = explode(',', $data);

      if (count($array) > 1) {
        return $array;
      }

      return $data;
    }
}
