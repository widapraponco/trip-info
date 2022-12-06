<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\Event;
use App\Transformers\BaseTransformer;

/**
 * @OA\Schema(
 *     schema="DestinasiTransformer",
 *     type="object",
 *     properties={
 *         @OA\Property(property="id", type="string"),
 *         @OA\Property(property="attributes", type="object", properties={
 *
 *             @OA\Property(property="destinasi_id", type="integer"),
 *             @OA\Property(property="nama", type="string"),
 *             @OA\Property(property="tanggal_pelaksanaan", type="date"),
 *             @OA\Property(property="jam_mulai", type="integer"),
 *             @OA\Property(property="jam_berakhir", type="integer"),
 *             @OA\Property(property="tanggal_selesai", type="date"),
 *             @OA\Property(property="contact_person", type="string"),
 *             @OA\Property(property="rating", type="integer"),
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
class EventTransformer extends BaseTransformer
{
    protected array $availableIncludes = [
        'roles',
        'permissions',
    ];

    /**
     * A Fractal transformer.
     *
     * @param  \App\Models\Event  $event
     *
     * @return array
     */
    public function transform(Event $event)
    {
        $response = [
            'id'                  => self::forId($event),
            'destinasi_id'        => $event->destinasi_id,
            'nama'                => $event->nama,
            'tanggal_pelaksanaan' => $event->tanggal_pelaksanaan,
            'jam_mulai'           => $event->jam_mulai,
            'jam_berakhir'        => $event->jam_berakhir,
            'tanggal_selesai'     => $event->tanggal_selesai,
            'contact_person'      => $event->contact_person,
            'rating'              => $event->rating,
        ];

        $response = $this->filterData(
            $response,
            [

            ]
        );

        return $this->addTimesHumanReadable($event, $response);
    }

    /** @return string */
    public function getResourceKey(): string
    {
        return 'event';
    }
}
