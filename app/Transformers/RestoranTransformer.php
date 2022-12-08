<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\restoran;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class RestoranTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(restoran $restoran)
    {
        $response = [
            'id'                => self::forId($restoran),
            'nama'               => $restoran->nama,
            'alamat'             => $restoran->alamat,
            'deskripsi'        => $restoran->deskripsi,
            'tanggal_selesai'     => $restoran->tanggal_selesai,
            'contact_person'        => $restoran->contact_person,
            'rating'        => $restoran->rating,
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'restoran';
    }
}
