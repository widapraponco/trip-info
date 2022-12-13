<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\souvenir;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class souvenirtransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(souvenir $souvenir)
    {
        $response = [
            
            'id'              => self::forId($souvenir), 
            'nip'             => $souvenir->nip,
            'type'            => $souvenir->type,
            'category'        => $souvenir->category,
            'organized_by'    => $souvenir->organized_by,
            'year'            => $souvenir->year,
            'certificate'     => $souvenir->certificate,
            'other_info'      => $souvenir->other_info,
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'user_courses';
    }
}