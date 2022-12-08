<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\Event;
use App\Transformers\EventTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class EventController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="event__request_property",
     *      @OA\Property(property="destinasi_id", type="integer", example="destinasi_id 1"),
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="tanggal_pelaksanaan", type="date", example="tanggal_pelaksanaan 1"),
     *      @OA\Property(property="jam_mulai", type="integer", example="jam_mulai 1")
     *      @OA\Property(property="jam_berakhir", type="integer", example="jam_berakhir 1"),
     *      @OA\Property(property="tanggal_selesai", type="date", example="tanggal_selesai 1"),
     *      @OA\Property(property="contact_person", type="string", example="contact_person 1"),
     *      @OA\Property(property="rating", type="integer", example="rating 1")
     * )
     * 
     * @OA\Schema(
     *      schema="event__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="event"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="destinasi_id", type="string", example="destinasi_id 1"),
     *                  @OA\Property(property="nama", type="string", example="nama 1"),
     *                  @OA\Property(property="tanggal_pelaksanaan", type="string", example="tanggal_pelaksanaan 1"),
     *                  @OA\Property(property="jam_mulai", type="integer", example="jam_mulai1")
     *                  @OA\Property(property="jam_berakhir", type="string", example="jam_berakhir 1"),
     *                  @OA\Property(property="tanggal_selesai", type="string", example="tanggal_selesai 1"),
     *                  @OA\Property(property="contact_person", type="string", example="contact_person 1"),
     *                  @OA\Property(property="rating", type="integer", example="rating 1")
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
     *     path="/event",
     *     summary="Get event",
     *     tags={"Event"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="destinasi_id", in="query", required=false,),
     *     @OA\Parameter(name="nama", in="query", required=false,),
     *     @OA\Parameter(name="tanggal_pelaksanaan", in="query", required=false,),
     *     @OA\Parameter(name="jam_mulai", in="query", required=false,),
     *     @OA\Parameter(name="jam_berakhir", in="query", required=false,),
     *     @OA\Parameter(name="jam_selesai", in="query", required=false,),
     *     @OA\Parameter(name="contact_person", in="query", required=false,),
     *     @OA\Parameter(name="rating", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/event__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(Event::class)
                ->allowedFilters(['destinasi_id', 'nama', 'tanggal_pelaksanaan', 'jam_mulai','jam_berakhir','tanggal_selesai','contact_person','rating'])
                ->paginate(),
            new EventTransformer()
        );
    }

    /**
     * @api                {get} /event/{id}
     * 
     * @OA\Get(
     *     path="/event/{id}",
     *     summary="Get event By Id",
     *     tags={"Event"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/event__response_property")
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
            new EventTransformer()
        );
    }

    /**
     * @OA\Post(
     *     path="/event",
     *     summary="Create event",
     *     tags={"Event"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/event__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/event__response_property")
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
                'destinasi_id'           => 'required|integer',
                'nama'                   => 'required|string',
                'tanggal_pelaksanaan'    => 'required|date',
                'jam_mulai'              => 'required|integer',
                'jam_berakhir'           => 'required|integer',
                'tanggal_selesai'        => 'required|date',
                'contact_person'         => 'required|string',
                'rating'                 => 'required|integer',
                
            ]
        );

        return $this->fractal(
            app(CreateUserAction::class)->execute($attributes),
            new EventTransformer()
        )
            ->respond(201);
    }

    /**
     * @api                {put} /event
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/event/{id}",
     *     summary="Update event",
     *     tags={"Event"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/event__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/event__response_property")
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
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'deskripsi' => 'required|string',
                'kota_id'   => 'required|integer',
            ]
        );

        $event = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        $event->update($attributes);

        return $this->fractal($event->refresh(), new EventTransformer());
    }

    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/event/{id}",
     *     summary="Delete event",
     *     tags={"Event"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/event__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/event__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */

    public function destroy(string $id)
    {
        $event = app(FindUserByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $event->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $event->delete();

        return response('', 204);
    }
}