<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\SaranaPrasarana;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class SaranaPrasaranaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(SaranaPrasarana $SaranaPrasarana)
    {
        $response = [
            'id' => self::forId($SaranaPrasarana),
            'destinasi' => $SaranaPrasarana->destinasi,
            'nama' => $SaranaPrasarana->nama,
            'kategori' => $SaranaPrasarana->kategori,
            'deskripsi'=> $SaranaPrasarana->deskripsi,
            'contact' => $SaranaPrasarana->contact,
            'rating' => $SaranaPrasarana->rating
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'SaranaPrasarana';
    }
}
