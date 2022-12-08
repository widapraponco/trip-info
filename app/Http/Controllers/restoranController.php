<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\restoran;
use App\Transformers\restoranTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class restoranController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="restoran__request_property",
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="alamat", type="string", example="alamat 1"),
     *      @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *      @OA\Property(property="tanggal_selesai", type="date", example="2022-12-30"),
     *      @OA\Property(property="contact_person", type="char", example="+6285816132823"),
     *      @OA\Property(property="rating", type="char", example="4.5")
     * )
     * 
     * @OA\Schema(
     *      schema="restoran__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="destinasi"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="nama", type="string", example="nama 1"),
     *                  @OA\Property(property="alamat", type="string", example="alamat 1"),
     *                  @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *                  @OA\Property(property="tanggal_selesai", type="date", example="2022-12-30"),
     *                  @OA\Property(property="contact_person", type="char", example="+6285816132823"),
     *                  @OA\Property(property="rating", type="char", example="4.5")
     *              ),  
     *          )
     *      )
     * )
     * 
     * issue: https://github.com/zircote/swagger-php/issues/695 (swagger doesn't accep square bracket)
     */

    public function __construct()
    {
        $permissions = User::PERMISSIONS;

        $this->middleware('permission:'.$permissions['index'], ['only' => 'index']);
        $this->middleware('permission:'.$permissions['create'], ['only' => 'store']);
        $this->middleware('permission:'.$permissions['show'], ['only' => 'show']);
        $this->middleware('permission:'.$permissions['update'], ['only' => 'update']);
        $this->middleware('permission:'.$permissions['destroy'], ['only' => 'destroy']);
    }


    /**
     * @OA\Get(
     *     path="/restoran",
     *     summary="Get restoran",
     *     tags={"restoran"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="nama", in="query", required=false,),
     *     @OA\Parameter(name="alamat", in="query", required=false,),
     *     @OA\Parameter(name="deskripsi", in="query", required=false,),
     *     @OA\Parameter(name="tanggal_selesai", in="query", required=false,),
     *     @OA\Parameter(name="contact_person", in="query", required=false,),
     *     @OA\Parameter(name="rating", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/restoran__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(restoran::class)
                ->allowedFilters(['nama', 'alamat', 'deskripsi', 'tanggal_selesai', 'contact_person', 'rating'])
                ->paginate(),
            new restoranTransformer()
        );
    }

    /**
     * @api                {get} /restoran/{id}
     * 
     * @OA\Get(
     *     path="/restoran/{id}",
     *     summary="Get restoran By Id",
     *     tags={"restoran"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/restoran__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */
    public function show(string $id)
    {
        return $this->fractal(
            app(FindUserByRouteKeyAction::class)->execute($id, throw404: true),
            new restoranTransformer()
        );
    }

    /**
     * @OA\Post(
     *     path="/restoran",
     *     summary="Create restoran",
     *     tags={"restoran"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/restoran__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/restoran__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */
    public function store(Request $request)
    {
        $attributes = $this->validate(
            $request,
            [
                'id'                => 'required|string',
                'nama'              => 'required|string',
                'alamat'            => 'required|string',
                'deskripsi'         => 'required|string',
                'tanggal_selesai'   => 'required|date',
                'contact_person'    => 'required|char',
                'rating'            => 'required|char',
            ]
        );

        return $this->fractal(
            app(CreateUserAction::class)->execute($attributes),
            new restoranTransformer()
        )
            ->respond(201);
    }

    /**
     * @api                {put} /restoran
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/restoran/{id}",
     *     summary="Update restoran",
     *     tags={"restoran"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/restoran__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/restoran__response_property")
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
                'id'                => 'required|string',
                'nama'              => 'required|string',
                'alamat'            => 'required|string',
                'deskripsi'         => 'required|string',
                'tanggal_selesai'   => 'required|date',
                'contact_person'    => 'required|char',
                'rating'            => 'required|char',
            ]
        );

        $restoran = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        $restoran->update($attributes);

        return $this->fractal($restoran->refresh(), new restoranTransformer());
    }

    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/restoran/{id}",
     *     summary="Delete restoran",
     *     tags={"restoran"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/restoran__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/restoran__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */

    public function destroy(string $id)
    {
        $restoran = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $restoran->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $restoran->delete();

        return response('', 204);
    }
}