<?php

namespace Rogue\Http\Transformers\Three;

use Rogue\Models\Signup;
use Rogue\Services\Registrar;
use League\Fractal\TransformerAbstract;

class AcceptedQuantityTransformer extends TransformerAbstract
{
    /**
     * Transform resource data.
     *
     * @param int $quantity
     * @return array
     */
    public function transform($quantity)
    {
        return ['quantity' => $quantity];
    }
}
