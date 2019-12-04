<?php

namespace Rogue\Http\Transformers;

use Rogue\Models\ActionStat;
use League\Fractal\TransformerAbstract;

class ActionStatTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param \Rogue\Models\ActionStat $actionStat
     * @return array
     */
    public function transform(ActionStat $actionStat)
    {
        $result = $actionStat;
        unset($result->deleted_at);

        return $result;
    }
}
