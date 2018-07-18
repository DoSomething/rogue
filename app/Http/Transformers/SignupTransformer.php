<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Signup;
use League\Fractal\ParamBag;
use Rogue\Services\Registrar;
use League\Fractal\TransformerAbstract;
use Illuminate\Validation\ValidationException;

class SignupTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'posts', 'user', 'accepted_quantity',
    ];

    /**
     * List of params possible to include
     *
     * @var array
     */
    protected $validParams = ['type'];

    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Signup $signup
     * @return array
     */
    public function transform(Signup $signup)
    {
        $response = [
            'id' => $signup->id,
            'northstar_id' => $signup->northstar_id,
            'campaign_id' => $signup->campaign_id,
            'campaign_run_id' => $signup->campaign_run_id,
            'quantity' => $signup->getQuantity(),
            'created_at' => $signup->created_at->toIso8601String(),
            'updated_at' => $signup->updated_at->toIso8601String(),
        ];

        if (is_staff_user() || auth()->id() === $signup->northstar_id) {
            $response['why_participated'] = $signup->why_participated;
            $response['source'] = $signup->source;
            $response['source_details'] = $signup->source_details;
            $response['details'] = $signup->details;
        }

        return $response;
    }

    /**
     * Include posts
     *
     * @param \Rogue\Models\Signup $signup
     * @param \League\Fractal\ParamBag|null
     * @return \League\Fractal\Resource\Collection
     */
    public function includePosts(Signup $signup, ParamBag $params = null)
    {
        // Validate `type` parameter, if provided:
        if ($params) {
            $usedParams = array_keys(iterator_to_array($params));

            if ($invalidParams = array_diff($usedParams, $this->validParams)) {
                throw ValidationException::withMessages([
                    'include' => 'Parameters must be one of: ' . implode(',', $this->validParams),
                ]);
            }

            return $this->collection($signup->visiblePosts($params->get('type')), new PostTransformer);
        } else {
            return $this->collection($signup->visiblePosts, new PostTransformer);
        }
    }

    /**
     * Include the user data (optionally)
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Item
     */
    public function includeUser(Signup $signup)
    {
        $registrar = app(Registrar::class);
        $northstar_id = $signup->northstar_id;

        return $this->item($registrar->find($northstar_id), new UserTransformer);
    }

    /**
     * Include accepted quantity
     *
     * @param \Rogue\Models\Signup $signup
     * @return \League\Fractal\Resource\Collection
     */
    public function includeAcceptedQuantity(Signup $signup)
    {
        return $this->item($signup->getAcceptedQuantity(), new AcceptedQuantityTransformer);
    }
}
