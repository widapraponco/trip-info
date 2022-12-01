<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers\DestinasiTransformer;

use App\Models\Destinasi;
use App\Transformers\BaseTransformer;

/**
 * @OA\Schema(
 *     schema="DestinasiTransformer",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="string"),
 *         @OA\Property(property="attributes", type="object", properties={
 *
 *             @OA\Property(property="nama", type="string"),
 *             @OA\Property(property="alamat", type="string"),
 *             @OA\Property(property="deskripsi", type="string"),
 *             @OA\Property(property="kota_id", type="integer"),
 *             @OA\Property(property="created_at", type="string"),
 *             @OA\Property(property="created_at_readable", type="string"),
 *             @OA\Property(property="updated_at", type="string"),
 *             @OA\Property(property="updated_at_readable", type="string")
 *
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
class UserTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'roles',
        'permissions',
    ];

    /**
     * A Fractal transformer.
     *
     * @param  \App\Models\Destinasi  $destinasi
     *
     * @return array
     */
    public function transform(User $destinasi)
    {
        $response = [
            'id'        => self::forId($destinasi),
            'nama'      => $destinasi->nama,
            'alamat'    => $destinasi->alamat,
            'deskripsi' => $destinasi->deskripsi,
            'kota_id'   => $destinasi->kota_id,
        ];

        $response = $this->filterData(
            $response,
            [

            ]
        );

        return $this->addTimesHumanReadable($destinasi, $response);
    }

    public function includeRoles(User $destinasi)
    {
        return $this->collection($destinasi->roles, new RoleTransformer());
    }

    public function includePermissions(User $destinasi)
    {
        return $this->collection($destinasi->permissions, new PermissionTransformer());
    }

    /** @return string */
    public function getResourceKey(): string
    {
        return 'destinasi';
    }
}
