<?php

namespace Rogue\Http\Controllers;


use Rogue\Models\Signup;
use Rogue\Services\Registrar;
use Rogue\Services\Phoenix;
use Rogue\Models\Post;
use Illuminate\Support\Facades\DB;
use Rogue\Services\CampaignService;

class CampaignsController extends Controller
{
    protected $phoenix;

    protected $campaignService;

    public function __construct(Phoenix $phoenix)
    {
        $this->middleware('auth');
        $this->middleware('role:admin,staff');
        $this->registrar = new Registrar();
        $this->phoenix = $phoenix;
        $this->campaignService = new CampaignService;
    }

    /**
     * Show overview of campaigns.
     */
    public function index()
    {
        // $campaigns = DB::table('posts')
        //         ->join('signups', 'signups.id', '=', 'posts.signup_id')
        //         ->select('signups.campaign_id', DB::raw('count(posts.northstar_id)'))
        //         ->groupBy('signups.campaign_id')
        //         ->get();

        // dd($campaigns);
        //
        // THE DREAM:
        // $posts = Campaigns::withCount('signups.posts');

        // $posts = Post::with('content' => function($query) { $query->where('status', '=', 'approved')})->get();

        // $posts = Post::with(['content' => function ($query) {
        //     dd($query);
        //     $query->where('status', '=', 'approved');

        // }])->get();
        // $campaigns = DB::table('signups')->select('campaign_id')->groupBy('campaign_id')->get();
        // $campaignIds = collect($campaigns)->pluck('campaign_id')->toArray();
        $ids = $this->campaignService->getAllCampaigns();

        $params = [
            'campaigns' => implode(',', $ids),
            'count' => (int) count($ids), // Look into batchedCollection
        ];

        $phoenixCampaigns = $this->phoenix->getAllCampaigns($params);
        $phoenixCampaigns = collect($phoenixCampaigns['data']);

        $grouped = $phoenixCampaigns->groupBy(function($campaign) {
            if ($campaign['staff_pick']) {
                return 'Staff Pick';
            }

            $cause = $campaign['causes']['primary']['name'];

            return $cause;
        });

        $grouped = $grouped->toArray();
        // dd($grouped->toArray());

        // $posts = Post::with('content')->get();
        // // dd($posts);
        // foreach ($posts as $post) {
        //     dd($post);
        // }



        // dd($posts->content);

        // $staffPicks = collect([
        //     ['name' => 'Campaign 1', 'approved' => 53, 'pending' => 32, 'rejected' => 34, 'deleted' => 3],
        //     ['name' => 'Campaign 2', 'approved' => 54, 'pending' => 33, 'rejected' => 35, 'deleted' => 4],
        //     ['name' => 'Campaign 3', 'approved' => 55, 'pending' => 34, 'rejected' => 36, 'deleted' => 5],
        // ]);

        // $environment = collect([
        //     ['name' => 'Campaign 4', 'approved' => 53, 'pending' => 32, 'rejected' => 34, 'deleted' => 3],
        //     ['name' => 'Campaign 5', 'approved' => 54, 'pending' => 33, 'rejected' => 35, 'deleted' => 4],
        //     ['name' => 'Campaign 6', 'approved' => 55, 'pending' => 34, 'rejected' => 36, 'deleted' => 5],
        // ]);

        // $bullying = collect([
        //     ['name' => 'Campaign 7', 'approved' => 53, 'pending' => 32, 'rejected' => 34, 'deleted' => 3],
        //     ['name' => 'Campaign 8', 'approved' => 54, 'pending' => 33, 'rejected' => 35, 'deleted' => 4],
        //     ['name' => 'Campaign 9', 'approved' => 55, 'pending' => 34, 'rejected' => 36, 'deleted' => 5],
        // ]);

        return view('pages.campaign_overview')
            ->with('state', $grouped);
    }

    /**
     * Show particular campaign inbox.
     */
    public function show($campaign_run_id)
    {
        // Pull in all signups for the given run that have pending posts, and include their pending posts
        $signups = Signup::whereHas('posts', function ($query) {
            $query->where('status', 'pending');
        })->where('campaign_run_id', $campaign_run_id)->with('posts')->get();

        // For each post, get and include the user
        $signups->each(function ($item) {
            $item->posts->each(function ($item) {
                $user = $this->registrar->find($item->northstar_id);
                $item->user = $user->toArray();
            });
        });

        return view('pages.campaign_inbox')
            ->with('state', [
                'signups' => $signups,
            ]);
    }
}
