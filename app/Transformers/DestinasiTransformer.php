<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\Destinasi;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class DestinasiTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(Destinasi $destinasi)
    {
        $response = [
            'id'                 => self::forId($destinasi),
            'nama'               => $destinasi->nama,
            'alamat'             => $destinasi->alamat,
            'deskripsi'          => $destinasi->deskripsi,
            'kota_id'            => $destinasi->kota_id
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'destinasi';
    }
}