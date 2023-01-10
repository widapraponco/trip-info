<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */

namespace App\Transformers;

use App\Models\sarana_prasarana;
use App\Transformers\BaseTransformer;
use League\Fractal\TransformerAbstract;

class sarana_prasaranaTransformer extends BaseTransformer
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    public function transform(sarana_prasarana $sarana_prasarana)
    {
        $response = [
            'id' => self::forId($sarana_prasarana),
            'destinasi' => $sarana_prasarana->destinasi,
            'nama' => $sarana_prasarana->nama,
            'kategori' => $sarana_prasarana->kategori,
            'deskripsi'=> $sarana_prasarana->deskripsi,
            'contact' => $sarana_prasarana->contact,
            'rating' => $sarana_prasarana->rating
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'sarana_prasarana';
    }
}
