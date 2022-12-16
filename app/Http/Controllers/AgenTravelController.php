<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\AgenTravel;
use App\Transformers\AgenTravelTransformer;
use Domain\User\Actions\CreateAgenTravelAction;
use Domain\User\Actions\FindAgenTravelByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Log;


class AgenTravelController extends Controller
{
    /** 
     * @OA\Schema(
     *      schema="agentravel__request_property",
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="alamat", type="string", example="alamat 1"),
     *      @OA\Property(property="contact_person", type="string", example="contact_person 1"),
     *      @OA\Property(property="rating", type="string", example="rating 1"),
     *      @OA\Property(property="destinasi_id", type="string", example="1")
     * )
     * 
     * @OA\Schema(
     *      schema="agentravel__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="agentravel"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="nama", type="string", example="nama 1"),
     *                  @OA\Property(property="alamat", type="string", example="alamat 1"),
     *                  @OA\Property(property="contact_person", type="string", example="contact_person 1"),
     *                  @OA\Property(property="rating", type="string", example="rating 1"),
     *                  @OA\Property(property="destinasi_id", type="string", example="1")
     *              ),  
     *          )
     *      )
     * )
     * 
     * issue: http://github.com/zircote/swagger-php/issues/695 (swagger doesn't accep square bracket)
     */

     
    /**
     * @OA\Get(
     *     path="/agentravel",
     *     summary="Get agentravel",
     *     tags={"AgenTravel"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="nama", in="query", required=false,),
     *     @OA\Parameter(name="alamat", in="query", required=false,),
     *     @OA\Parameter(name="contact_person", in="query", required=false,),
     *     @OA\Parameter(name="rating", in="query", required=false,),
     *     @OA\Parameter(name="destinasi_id", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/agentravel__response_property")
     *             )
     *         }
     *     ),
     * )
     */

    public function index()
    {
        return $this->fractal(
            QueryBuilder::for(AgenTravel::class)
                ->allowedFilters(['nama', 'alamat', 'contact_person', 'rating',  'destinasi_id'])
                ->paginate(),
            new AgenTravelTransformer()
        );
    }

    /**
     * @api                {get} /agentravel/{id}
     * 
     * @OA\Get(
     *     path="/agentravel/{id}",
     *     summary="Get agentravel By Id",
     *     tags={"AgenTravel"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/agentravel__response_property")
     *             )
     *         }
     *     ),
     *     
     * )
     */
    public function show(string $id)
    {
        return $this->fractal(
            app(FindAgenTravelByRouteKeyAction::class)->execute($id, throw404: true),
            new AgenTravelTransformer()
        );
    }

    /**
     * @OA\Post(
     *     path="/agentravel",
     *     summary="Create agentravel",
     *     tags={"AgenTravel"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/agentravel__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/agentravel__response_property")
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
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'contact_person' => 'required|string',
                'rating' => 'required|string',
                'destinasi_id'   => 'required|string',
            ]
        );

        return $this->fractal(
            app(CreateAgenTravelAction::class)->execute($attributes),
            new AgenTravelTransformer()
        )
            ->respond(201);
    }

    /**
     * @api                {put} /agentravel
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/agentravel/{id}",
     *     summary="Update agentravel",
     *     tags={"AgenTravel"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/agentravel__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/agentravel__response_property")
     *             )
     *         }
     *     ),
     *    
     * )
     */
    public function update(Request $request, string $id)
    {
        $attributes = $this->validate(
            $request,
            [
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'contact_person' => 'required|string',
                'rating'    => 'required|string',
                'destinasi_id'   => 'required|string',
            ]
        );

        $agentravel = app(FindAgenTravelByRouteKeyAction::class)
            ->execute($id);

        $agentravel->update($attributes);

        return $this->fractal($agentravel->refresh(), new AgenTravelTransformer());
    }

    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/agentravel/{id}",
     *     summary="Delete agentravel",
     *     tags={"AgenTravel"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/agentravel__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/agentravel__response_property")
     *             )
     *         }
     *     ),
     *     
     * )
     */

    public function destroy(string $id)
    {
        $agentravel = app(FindAgenTravelByRouteKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $agentravel->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $agentravel->delete();

        return response('', 204);
    }
}