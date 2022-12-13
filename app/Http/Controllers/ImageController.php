<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\Image;
use App\Transformers\ImageTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Domain\Image\Actions\CreateImageAction;
use Domain\Image\Actions\FindImageByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Log;


class ImageController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="image__request_property",
     *      @OA\Property(property="name", type="string", example="name 1"),
     *      @OA\Property(property="originalname", type="string", example="originalname 1"),
     *      @OA\Property(property="originalextension", type="string", example="originalextension 1"),
     *      @OA\Property(property="path", type="string", example="path 1"),
     *      @OA\Property(property="size", type="string", example="size 1"),
     *      @OA\Property(property="mimeType", type="string", example="mimeType 1")
     * )
     * 
     * @OA\Schema(
     *      schema="image__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="image"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="name", type="string", example="name 1"),
     *                  @OA\Property(property="originalname", type="string", example="originalname 1"),
     *                  @OA\Property(property="originalextension", type="string", example="originalextension 1"),
     *                  @OA\Property(property="path", type="string", example="path 1"),
     *                  @OA\Property(property="size", type="string", example="size 1"),
     *                  @OA\Property(property="mimeType", type="string", example="mimeType 1")
     *              ),  
     *          )
     *      )
     * )
     * 
     * issue: https://github.com/zircote/swagger-php/issues/695 (swagger doesn't accep square bracket)
     */

     
    /**
     * @OA\Get(
     *     path="/image",
     *     summary="Get image",
     *     tags={"Image"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="name", in="query", required=false,),
     *     @OA\Parameter(name="originalname", in="query", required=false,),
     *     @OA\Parameter(name="originalextension", in="query", required=false,),
     *     @OA\Parameter(name="path", in="query", required=false,),
     *     @OA\Parameter(name="size", in="query", required=false,),
     *     @OA\Parameter(name="mimeType", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/image__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(Image::class)
                ->allowedFilters(['name', 'originalname', 'originalextension', 'path', 'size', 'mimeType'])
                ->paginate(),
            new ImageTransformer()
        );
    }

    /**
     * @api                {get} /image/{id}
     * 
     * @OA\Get(
     *     path="/image/{id}",
     *     summary="Get image By Id",
     *     tags={"Image"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/image__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */
    public function show(string $id)
    {
        return $this->fractal(
            app(FindImageByRouteKeyAction::class)->execute($id, throw404: true),
            new ImageTransformer()
        );
    }

    /**
     * @OA\Post(
     *     path="/image",
     *     summary="Create image",
     *     tags={"Image"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/image__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/image__response_property")
     *             )
     *         }
     *     ),
     * )
     */
    public function store(Request $request)
    {
        $attributes = $this->validate(
            $request,
            [
                'name'                  => 'required|string',
                'originalname'          => 'required|string',
                'originalextension'     => 'required|string',
                'path'                  => 'required|string',
                'size'                  => 'required|string',
                'mimeType'              => 'required|string',
            ]
        );

        return $this->fractal(
            app(CreateImageAction::class)->execute($attributes),
            new ImageTransformer()
        )
            ->respond(201);
    }

    /**
     * @api                {put} /image
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/image/{id}",
     *     summary="Update image",
     *     tags={"Image"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/image__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/image__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */
    public function update(Request $request, string $id)
    {
        $attributes = $this->validate(
            $request,
            [
                'name'                  => 'required|string',
                'originalname'          => 'required|string',
                'originalextension'     => 'required|string',
                'path'                  => 'required|string',
                'size'                  => 'required|string',
                'mimeType'              => 'required|string',
            ]
        );
        $image = app(FindImageByRouteKeyAction::class)
            ->execute($id);

        $image->update($attributes);

        return $this->fractal($image->refresh(), new ImageTransformer());
    }

    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/image/{id}",
     *     summary="Delete image",
     *     tags={"Image"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/image__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/image__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */

    public function destroy(string $id)
    {
        $image = app(FindImageByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $image->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $image->delete();

        return response('', 204);
    }
}