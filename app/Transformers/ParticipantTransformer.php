<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\Participant;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class ParticipantTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(Participant $participant)
    {
        $response = [
            'id'          => self::forId($participant),
            'register_at' => $participant->register_at,
            'status'      => $participant->status,
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'participant';
    }
}
