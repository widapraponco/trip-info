<?php
/**
 * Created by PhpStorm.
 * User: Lloric Mayuga Garcia <lloricode@gmail.com>
 * Date: 11/24/18
 * Time: 3:31 PM
 */


namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    protected array $availableIncludes = [];
    protected array $defaultIncludes = [];

    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform()
    {
        $response = [
            'id'                    => self::forId($image),
            'nama'                  => $image->nama,
            'extension'             => $image->extension,
            'path'                  => $this->getUrl($image),
            'size'                  => $image->size,
        ];
        return $response;
    }

    public function getResourceKey(): string
    {
        return 'image';
    }
}
