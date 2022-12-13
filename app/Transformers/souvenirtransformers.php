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
            
            'id'              => self::forId($courses), 
            'nip'             => $courses->nip,
            'type'            => $courses->type,
            'category'        => $courses->category,
            'organized_by'    => $courses->organized_by,
            'year'            => $courses->year,
            'certificate'     => $courses->certificate,
            'other_info'      => $courses->other_info,
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'user_courses';
    }
}