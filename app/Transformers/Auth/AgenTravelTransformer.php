<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\AgenTravel;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class AgenTravelTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(AgenTravel $AgenTravel)
    {
        $response = [
            'id'                 => self::forId($AgenTravel),
            'nama'               => $AgenTravel->nama,
            'alamat'             => $AgenTravel->alamat,
            'deskripsi'          => $AgenTravel->deskripsi,
            'kota_id'            => $AgenTravel->kota_id
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'AgenTravel';
    }
}