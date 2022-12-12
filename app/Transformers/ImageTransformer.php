<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\Image;
use App\Transformers\BaseTransformer;

/**
 * @OA\Schema(
 *     schema="ImageTransformer",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="string"),
 *         @OA\Property(property="attributes", type="object", properties={
 *
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="pic", type="string"),
 *         }),
 *         @OA\Property(property="relationships", type="array", @OA\Items({
 *
 *         })),
 *         @OA\Property(property="meta", type="array", @OA\Items({
 *
 *             @OA\Property(property="include", type="array", @OA\Items({
 *             })),
 *         })),
 *     }
 * )
 */
class ImageTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'roles',
        'permissions',
    ];

    /**
     * A Fractal transformer.
     *
     * @param  \App\Models\Image  $image
     *
     * @return array
     */
    public function transform(Image $image)
    {
        $response = [
            'id'                    => self::forId($image),
            'name'                  => $image->name,
            'originalname'          => $image->originalname,
            'originalextension'     => $image->originalextension,
            'path'                  => $image->path,
            'size'                  => $image->size,
            'minetype'              => $image->minetype,
        ];

        $response = $this->filterData(
            $response,
            [

            ]
        );

        return $this->addTimesHumanReadable($image, $response);
    }

    /** @return string */
    public function getResourceKey(): string
    {
        return 'image';
    }
}