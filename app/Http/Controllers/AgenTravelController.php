<?php

declare(strict_types=1);

namespace App\Http\Controllers\DestinasiController;

use App\Http\Controllers\Controller;
use App\Models\AgenTravel;
use App\Transformers\AgenTravelTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class AgenTravelController extends Controller
{
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
    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(User::class)
                ->allowedFilters(['nama', 'alamat', 'contact_person', 'destinasi_id', 'rating'])
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
            new AgenTRavelTransformer()
        );
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * @api                {post} /auth/users Store user
     * @apiName            store-user
     * @apiGroup           User
     * @apiVersion         1.0.0
     * @apiPermission      Authenticated User
     * @apiUse             UserCreatedResponse
     * @apiParam {String} nama (required)
     * @apiParam {String} alamat (required)
     * @apiParam {String} deskripsi (required)
     * @apiParam {integer} kota_id (required)
     *
     */
    public function store(Request $request)
    {
        $attributes = $this->validate(
            $request,
            [
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'contact_person' => 'required|string',
                'destinasi_id'   => 'required|integer',
                'rating'   => 'required|string',
            ]
        );

        return $this->fractal(
            app(CreateAgenTravelAction::class)->execute($attributes),
            new AgenTravelTransformer()
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
     * @apiParam {String} contact_person
     * @apiParam {integer} destinasi_id
     * @apiParam {String} rating
     *
     */
    public function update(Request $request, string $id)
    {
        $attributes = $this->validate(
            $request,
            [
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'contact_person' => 'required|string',
                'destinasi_id'   => 'required|integer',
                'rating'   => 'required|string',
            ]
        );

        $agentravel = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        $agentravel->update($attributes);

        return $this->fractal($agentravel->refresh(), new AgenTravelTransformer());
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
        $agentravel = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $agentravel->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $agentravel->delete();

        return response('', 204);
    }
}