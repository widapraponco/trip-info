<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\SaranaPrasarana;
use App\Transformers\SaranaPrasaranaTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Domain\SaranaPrasarana\Actions\CreateSaranaPrasaranaAction;
use Domain\SaranaPrasarana\Actions\FindSaranaPrasaranaByKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Log;


class SaranaPrasaranaController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="destinasi__request_property",
     *      @OA\Property(property="destinasi", type="string", example="nama 1"),
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="kategori", type="string", example="alamat 1"),
     *      @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *      @OA\Property(property="contact", type="integer", example="1"),
     *      @OA\Property(property="rating", type="integer", example="1")
     * )
     * 
     * @OA\Schema(
     *      schema="SaranaPrasarana__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="SaranaPrasarana"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="SaranaPrasarana", type="string", example="nama 1"),
     *                  @OA\Property(property="nama", type="string", example="nama 1"),
     *                  @OA\Property(property="kategori", type="string", example="alamat 1"),
     *                  @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *                  @OA\Property(property="contact", type="integer", example="1"),
     *                  @OA\Property(property="rating", type="integer", example="1")
     *              ),  
     *          )
     *      )
     * )
     * 
     * issue: https://github.com/zircote/swagger-php/issues/695 (swagger doesn't accep square bracket)
     */

    /**
     * @OA\Get(
     *     path="/sarana_prasarana",
     *     summary="Get sarana_prasarana",
     *     tags={"SaranaPrasarana"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="sarana_prasarana", in="query", required=false,),
     *     @OA\Parameter(name="nama", in="query", required=false,),
     *     @OA\Parameter(name="kategori", in="query", required=false,),
     *     @OA\Parameter(name="deskripsi", in="query", required=false,),
     *     @OA\Parameter(name="contact", in="query", required=false,),
     *     @OA\Parameter(name="rating", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/sarana_prasarana__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(SaranaPrasarana::class)
                ->allowedFilters(['id', 'destinasi', 'kategori', 'deskripsi', 'contact', 'rating'])
                ->paginate(),
            new SaranaPrasaranaTransformer()
        );
    }

    /**
     * @api                {get} /SaranaPrasana/{id}
     * 
     * @OA\Get(
     *     path="/SaranaPrasana/{id}",
     *     summary="Get SaranaPrasana By Id",
     *     tags={"SaranaPrasana"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/SaranaPrasana__response_property")
     *             )
     *         }
     *     ),
     * )
     */
    public function show(string $id)
    {
        return $this->fractal(
            app(FindSaranaPrasaranaByKeyAction::class)->execute($id, throw404: true),
            new SaranaPrasaranaTransformer()
        );
    }

    /**
     * @OA\Post(
     *     path="/SaranaPrasana",
     *     summary="Create SaranaPrasana",
     *     tags={"Destinasi"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/SaranaPrasana__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/SaranaPrasana__response_property")
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
                'id'=> 'required|string',
                'destinasi' => 'required|string',
                'nama' => 'required|string', 
                'kategori' => 'required|string', 
                'deskripsi' => 'required|string', 
                'contact' => 'required|string', 
                'rating' => 'required|string',
                
            ]
        );

        return $this->fractal(
            app(CreateSaranaPrasaranaAction::class)->execute($attributes),
            new SaranaPrasaranaTransformer()
        )
            ->respond(201);
    }

    /**
     * @api                {put} /SaranaPrasana
     * 
     * @OA\Put(
     *     path="/SaranaPrasana/{id}",
     *     summary="Update SaranaPrasana",
     *     tags={"SaranaPrasana"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/SaranaPrasana__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/SaranaPrasana__response_property")
     *             )
     *         }
     *     ),
     * )
     */
    public function update(Request $request, string $id)
    {
        $attributes = $this->validate(
            $request,
            [
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'deskripsi' => 'required|string',
                'kota_id'   => 'required|integer',
            ]
        );

        $sarana_prasarana = app(FindSaranaPrasaranaByKeyAction::class)
            ->execute($id);

        $sarana_prasarana->update($attributes);

        return $this->fractal($sarana_prasarana->refresh(), new SaranaPrasaranaTransformer());
    }

    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @OA\Delete(
     *     path="/SaranaPrasana/{id}",
     *     summary="Delete SaranaPrasana",
     *     tags={"SaranaPrasana"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/SaranaPrasana__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/SaranaPrasana__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function destroy(string $id)
    {
        $sarana_prasarana = app(FindSaranaPrasaranaByKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $sarana_prasarana->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $sarana_prasarana->delete();
        return response('', 204);
    }
}