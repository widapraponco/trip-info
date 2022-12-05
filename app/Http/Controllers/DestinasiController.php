<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\Destinasi;
use App\Transformers\DestinasiTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class DestinasiController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="destinasi__request_property",
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="alamat", type="string", example="alamat 1"),
     *      @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *      @OA\Property(property="kota_id", type="integer", example="1")
     * )
     * 
     * @OA\Schema(
     *      schema="destinasi__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="destinasi"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="nama", type="string", example="nama 1"),
     *                  @OA\Property(property="alamat", type="string", example="alamat 1"),
     *                  @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *                  @OA\Property(property="kota_id", type="integer", example="1")
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

        // $this->middleware('permission:'.$permissions['index'], ['only' => 'index']);
        $this->middleware('permission:'.$permissions['create'], ['only' => 'store']);
        $this->middleware('permission:'.$permissions['show'], ['only' => 'show']);
        $this->middleware('permission:'.$permissions['update'], ['only' => 'update']);
        $this->middleware('permission:'.$permissions['destroy'], ['only' => 'destroy']);
    }
    
    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Spatie\Fractal\Fractal
     * @api                {get} /auth/users Get all users
     * @apiName            get-all-users
     * @apiGroup           User
     * @apiVersion         1.0.0
     * @apiPermission      Authenticated User
     * @apiUse             UsersResponse
     *
     */
    /**
     * @OA\Get(
     *     path="/destinasi",
     *     summary="Get destinasi",
     *     tags={"Destinasi"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="nama", in="query", required=false,),
     *     @OA\Parameter(name="alamat", in="query", required=false,),
     *     @OA\Parameter(name="deskripsi", in="query", required=false,),
     *     @OA\Parameter(name="kota_id", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/destinasi__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(Destinasi::class)
                ->allowedFilters(['nama', 'alamat', 'deskripsi', 'kota_id'])
                ->paginate(),
            new DestinasiTransformer()
        );
    }

    /**
     * @param  string  $id
     *
     * @return \Spatie\Fractal\Fractal
     * @api                {get} /auth/users/{id} Show user
     * @apiName            show-user
     * @apiGroup           User
     * @apiVersion         1.0.0
     * @apiPermission      Authenticated User
     * @apiUse             UserResponse
     *
     */
    public function show(string $id)
    {
        return $this->fractal(
            app(FindUserByRouteKeyAction::class)->execute($id, throw404: true),
            new DestinasiTransformer()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @api                {post} /destinasi
     * @apiPermission      Authenticated User
     * 
     * @OA\Post(
     *     path="/destinasi",
     *     summary="Create destinasi",
     *     tags={"Destinasi"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/destinasi__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/destinasi__response_property")
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
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'deskripsi' => 'required|string',
                'kota_id'   => 'required|integer',
            ]
        );

        return $this->fractal(
            app(CreateUserAction::class)->execute($attributes),
            new DestinasiTransformer()
        )
            ->respond(201);
    }

    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     *
     * @return \Spatie\Fractal\Fractal
     * @throws \Illuminate\Validation\ValidationException
     * @api                {put} /auth/users/ Update user
     * @apiName            update-user
     * @apiGroup           User
     * @apiVersion         1.0.0
     * @apiPermission      Authenticated User
     * @apiUse             UserResponse
     * @apiParam {String} nama
     * @apiParam {String} alamat
     * @apiParam {String} deskripsi
     * @apiParam {integer} kota_id
     *
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

        $destinasi = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        $destinasi->update($attributes);

        return $this->fractal($destinasi->refresh(), new DestinasiTransformer());
    }

    /**
     * @param  string  $id
     *
     * @return \Illuminate\Http\Response|\Laravel\Lumen\Http\ResponseFactory
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiName            destroy-user
     * @apiGroup           User
     * @apiVersion         1.0.0
     * @apiPermission      Authenticated User
     * @apiUse             NoContentResponse
     *
     */
    public function destroy(string $id)
    {
        $destinasi = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $destinasi->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $destinasi->delete();

        return response('', 204);
    }
}