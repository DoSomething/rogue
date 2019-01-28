<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\Action;
use League\Fractal\TransformerAbstract;

class ActionTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\Action $action
     * @return array
     */
    public function transform (Action $action)
    {
        return [

        ];
    }
}
