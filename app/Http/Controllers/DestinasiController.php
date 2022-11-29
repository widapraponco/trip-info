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
     *      schema="destinasi__request_property",
     *      @OA\Property(property="nama", type="string", example="nama 1"),
     *      @OA\Property(property="alamat", type="string", example="alamat 1"),
     *      @OA\Property(property="deskripsi", type="string", example="deskripsi 1"),
     *      @OA\Property(property="kota_id", type="string", example="1")
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
     *                  @OA\Property(property="kota_id", type="string", example="1")
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
     * @api                {post} /destinasi
     * @apiPermission      Authenticated User
     * 
     * @OA\Post(
     *     path="/destinasi",
     *     summary="Create deskripsi",
     *     tags={"Deskripsi"},
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
    public function create(Request $request)
    {
        $attributes = $this->validate(
            $request,
            [
                'nama'               => 'required|string',
                'alamat'             => 'required|string',
                'deskripsi'          => 'required|string',
                'kota_id'            => 'required|string',
            ]
        );

        $deskripsi = $this->destinasiRepository->create($attributes);
        // $deskripsi = Destinasi::all();
        return $this->fractal([$deskripsi], new DestinasiTransformer());
    }

    /**
     * @OA\Get(
     *     path="/destinasi",
     *     summary="Get deskripsi",
     *     tags={"Deskripsi"},
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
    public function find(Request $request)
    {
        $this->validate($request, [
            'page'                  => 'nullable|integer',
            'per_page'              => 'nullable|integer',
            'nama'                  => 'nullable|string',
            'alamat'                => 'nullable|string',
            'deskripsi'             => 'nullable|string',
            'kota_id'               => 'nullable|string',
        ]);

        $input = [
            'per_page'              => $request->input('per_page', false),
            'nama'                   => $request->input('nama', false),
            'alamat'                 => $request->input('alamat', false),
            'deskripsi'           => $request->input('deskripsi', false),
            'kota_id'           => $request->input('kota_id', false),
        ];
        $deskripsis = $this->destinasiRepository->findByParams($input);
        return $this->fractal($destinasis, new DestinasiTransformer());
    }

    /**
     * @api                {get} /destinasi/{id}
     * 
     * @OA\Get(
     *     path="/destinasi/{id}",
     *     summary="Get destinasi By Id",
     *     tags={"Destinasi"},
     *     @OA\Parameter(name="id", in="path", required=true,),
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
    public function findById(string $id)
    {
        if (!$id) {
            abort(404);
        }

        $destinasi = $this->destinasiRepository->findByRouteKeyName($id);

        if (blank($destinasi)) {
            return $this->getErrorMessage(105);
        }

        return $this->fractal([$destinasi], new DestinasiTransformer());
    }

    /**
     * @api                {put} /destinasi
     * @apiPermission      Authenticated User
     * 
     * @OA\Put(
     *     path="/destinasi/{id}",
     *     summary="Update destinasi",
     *     tags={"Destinasi"},
     *     @OA\Parameter(name="id", in="path", required=true,),
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
    public function update(Request $request, string $id)
    {
        $attributes = $this->validate(
            $request,
            [
                'nama'               => 'required|string',
                'alamat'             => 'required|string',
                'deskripsi'       => 'required|string',
                'kota_id'       => 'required|string',
            ]
        );

        $keyId = $this->destinasiRepository->findByRouteKeyName($id);
        if (empty($keyId)) {
            return response(['message' => 'Not found id.'], 403);
        }
        $id = $keyId->getKey();
        $destinasi = $this->destinasiRepository->update($attributes, $id);
        return $this->fractal([$destinasi], new DestinasiTransformer());
    }


    /**
     * @api                {delete} /auth/users/{id} Destroy user
     * @apiPermission      Authenticated User
     * @OA\Delete(
     *     path="/destinasi/{id}",
     *     summary="Delete destinasi",
     *     tags={"Destinasi"},
     *     @OA\Parameter(name="id", in="path", required=true,),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(ref="#/components/schemas/destinasi__request_property",)
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="ok"
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