<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Destinasi\DestinasiRepository;
use App\Models\Destinasi;
use App\Transformers\DestinasiTransformer;
use Illuminate\Support\Facades\Log;

class DestinasiController extends Controller
{
    protected array $permission = Destinasi::PERMISSIONS;
    protected DestinasiRepository $destinasiRepository;

    /** 
     * @OA\Schema(
     *      schema="user_education__request_property",
     *      @OA\Property(property="nip", type="string", example="001"),
     *      @OA\Property(property="grade", type="string", example="grade 1"),
     *      @OA\Property(property="school_name", type="string", example="school 1"),
     *      @OA\Property(property="departement", type="string", example="dept 1"),
     *      @OA\Property(property="location", type="string", example="location 1"),
     *      @OA\Property(property="graduate_at", type="date", example="2022-09-20"),
     *      @OA\Property(property="other_info", type="text", example="information 1")
     * )
     * 
     * @OA\Schema(
     *      schema="user_education__response_property",
     *      @OA\Property(property="data",type="array",
     *          @OA\Items(
     *              @OA\Property(property="type", type="string", example="user_education"),
     *              @OA\Property(property="id", type="string", example="1"),
     *              @OA\Property(
     *                  property="attributes", type="object",
     *                  @OA\Property(property="nip", type="string", example="001"),
     *                  @OA\Property(property="grade", type="string", example="grade 1"),
     *                  @OA\Property(property="school_name", type="string", example="school 1"),
     *                  @OA\Property(property="departement", type="string", example="dept 1"),
     *                  @OA\Property(property="location", type="string", example="location 1"),
     *                  @OA\Property(property="graduate_at", type="date", example="2022-09-20"),
     *                  @OA\Property(property="other_info", type="text", example="information 1")
     *              ),  
     *          )
     *      )
     * )
     * 
     * issue: https://github.com/zircote/swagger-php/issues/695 (swagger doesn't accep square bracket)
     */
    public function __construct(DestinasiRepository $destinasiRepository) 
    {
        $this->destinasiRepository = $destinasiRepository;

        $this->middleware(sprintf('permission:%s', $this->permission['create']), ['only' => ['create']]);
        $this->middleware(sprintf('permission:%s', $this->permission['update']), ['only' => ['update']]);
    }

    /**
     * @api                {post} /user-education
     * @apiPermission      Authenticated User
     * 
     * @OA\Post(
     *     path="/user-education",
     *     summary="Create education",
     *     tags={"Education"},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/user_education__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/user_education__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */
    public function create(Request $request)
    {
        $attributes = $this->validate(
            $request,
            [
                'nip'               => 'required|string',
                'grade'             => 'required|string',
                'school_name'       => 'required|string',
                'departement'       => 'required|string',
                'location'          => 'required|string',
                'graduate_at'       => 'required|date',
                'other_info'        => 'required|string'
            ]
        );

        $education = $this->destinasiRepository->create($attributes);
        // $education = Destinasi::all();
        return $this->fractal([$education], new DestinasiTransformer());
    }

    /**
     * @OA\Get(
     *     path="/user-education",
     *     summary="Get education",
     *     tags={"Education"},
     *     @OA\Parameter(name="page", in="query", required=false,),
     *     @OA\Parameter(name="per_page", in="query", required=false,),
     *     @OA\Parameter(name="nip", in="query", required=false,),
     *     @OA\Parameter(name="grade", in="query", required=false,),
     *     @OA\Parameter(name="school_name", in="query", required=false,),
     *     @OA\Parameter(name="departement", in="query", required=false,),
     *     @OA\Parameter(name="location_at", in="query", required=false,),
     *     @OA\Parameter(name="graduate_at", in="query", required=false,),
     *     @OA\Parameter(name="other_info", in="query", required=false,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/user_education__response_property")
     *             )
     *         }
     *     ),
     * )
     */
    public function find(Request $request)
    {
        $this->validate($request, [
            'page'                  => 'nullable|integer',
            'per_page'              => 'nullable|integer',
            'nip'                   => 'nullable|string',
            'grade'                 => 'nullable|string',
            'school_name'           => 'nullable|string',
            'departement'           => 'nullable|string',
            'location'              => 'nullable|string',
            'graduate_at'           => 'nullable|date',
            'other_info'            => 'nullable|string'
        ]);

        $input = [
            'per_page'              => $request->input('per_page', false),
            'nip'                   => $request->input('nip', false),
            'grade'                 => $request->input('grade', false),
            'school_name'           => $request->input('school_name', false),
            'departement'           => $request->input('departement', false),
            'location'              => $request->input('location', false),
            'graduate_at'           => $request->input('graduate_at', false),
            'other_info'            => $request->input('other_info', false),
        ];
        $educations = $this->destinasiRepository->findByParams($input);
        return $this->fractal($educations, new DestinasiTransformer());
    }

    /**
     * @api                {get} /user-education/{id}
     * 
     * @OA\Get(
     *     path="/user-education/{id}",
     *     summary="Get education By Id",
     *     tags={"Education"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/user_education__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */
    public function findById(string $id)
    {
        if (!$id) {
            abort(404);
        }

        $education = $this->destinasiRepository->findByRouteKeyName($id);

        if (blank($education)) {
            return $this->getErrorMessage(105);
        }

        return $this->fractal([$education], new DestinasiTransformer());
    }

    /**
     * @api                {put} /user-education
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/user-education/{id}",
     *     summary="Update education",
     *     tags={"Education"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/user_education__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok",
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/user_education__response_property")
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
                'nip'               => 'required|string',
                'grade'             => 'required|string',
                'school_name'       => 'required|string',
                'departement'       => 'required|string',
                'location'          => 'required|string',
                'graduate_at'       => 'required|date',
                'other_info'        => 'required|string'
            ]
        );

        $keyId = $this->destinasiRepository->findByRouteKeyName($id);
        if (empty($keyId)) {
            return response(['message' => 'Not found id.'], 403);
        }
        $id = $keyId->getKey();
        $education = $this->destinasiRepository->update($attributes, $id);
        return $this->fractal([$education], new DestinasiTransformer());
    }


    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/education/{id}",
     *     summary="Delete education",
     *     tags={"Education"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/user_education__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
     *         content={
     *             @OA\MediaType(
     *                 mediaType="application/json",
     *                 @OA\Schema(ref="#/components/schemas/user_education__response_property")
     *             )
     *         }
     *     ),
     *     security={{"authorization":{}}}
     * )
     */

    public function destroy(string $id)
    {
        $id = $this->destinasiRepository->findByRouteKeyName($id)->getKey();
        if (empty($id)) {
            return $this->getErrorMessage(105);
        }

        $this->destinasiRepository->delete($id);
        return response('', 204);
    }
}