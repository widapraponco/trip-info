<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\penginapan;
use App\Transformers\penginapanTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class penginapanController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="penginapan__request_property",
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="alamat", type="string", example="alamat 1"),
     *      @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *      @OA\Property(property="tanggal_selesai", type="date", example="2022-12-30"),
     *      @OA\Property(property="contact_person", type="char", example="+6285816132823"),
     *      @OA\Property(property="rating", type="char", example="4.5")
     * )
     * 
     * @OA\Schema(
     *      schema="penginapan__response_property",
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
     *     path="/penginapan",
     *     summary="Get penginapan",
     *     tags={"penginapan"},
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
     *                 @OA\Schema(ref="#/components/schemas/penginapan__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(penginapan::class)
                ->allowedFilters(['nama', 'alamat', 'deskripsi', 'tanggal_selesai', 'contact_person', 'rating'])
                ->paginate(),
            new penginapanTransformer()
        );
    }

    /**
     * @api                {get} /penginapan/{id}
     * 
     * @OA\Get(
     *     path="/penginapan/{id}",
     *     summary="Get penginapan By Id",
     *     tags={"penginapan"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/penginapan__response_property")
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
            new penginapanTransformer()
        );
    }

    /**
     * @OA\Post(
     *     path="/penginapan",
     *     summary="Create penginapan",
     *     tags={"penginapan"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/penginapan__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/penginapan__response_property")
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
            new penginapanTransformer()
        )
            ->respond(201);
    }

    /**
     * @api                {put} /penginapan
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/penginapan/{id}",
     *     summary="Update penginapan",
     *     tags={"penginapan"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/penginapan__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/penginapan__response_property")
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

        $penginapan = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        $penginapan->update($attributes);

        return $this->fractal($penginapan->refresh(), new penginapanTransformer());
    }

    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/penginapan/{id}",
     *     summary="Delete penginapan",
     *     tags={"penginapan"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/penginapan__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/penginapan__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */

    public function destroy(string $id)
    {
        $penginapan = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $penginapan->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $penginapan->delete();

        return response('', 204);
    }
}