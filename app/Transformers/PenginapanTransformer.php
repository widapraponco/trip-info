<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\penginapan;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class penginapanTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(penginapan $penginapan)
    {
        $response = [
            'id'                => self::forId($penginapan),
            'nama'               => $penginapan->nama,
            'alamat'             => $penginapan->alamat,
            'deskripsi'        => $penginapan->deskripsi,
            'tanggal_selesai'     => $penginapan->tanggal_selesai,
            'contact_person'        => $penginapan->contact_person,
            'rating'        => $penginapan->rating,
        ];
        return $response;
    }


    public function getResourceKey(): string
    {
        return 'penginapan';
    }
}