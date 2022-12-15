<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auth\User\User;
use App\Models\Destinasi;
use App\Models\Image;
use App\Transformers\DestinasiTransformer;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Actions\FindUserByRouteKeyAction;
use Domain\Destinasi\Actions\CreateDestinasiAction;
use Domain\Destinasi\Actions\FindDestinasiByRouteKeyAction;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Log;


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
    public function show(string $id)
    {
        return $this->fractal(
            app(FindDestinasiByKeyAction::class)->execute($id, throw404: true),
            new DestinasiTransformer()
        );
    }

    /**
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

        function images(array $files, string $destination)
        {
            $images=[];
            foreach ($files as $key => $value) {
                if(empty($value))continue;

                $images[] = $this->images($value, $destination, $key);
            }
            return $images;
        }

        function imagefile(UploadedFile $uploadFile, string $destination, string $name =null)
        {
            $path = $uploadFile->store($destination);// : $uploadfile->storeAs($destination.$name-'-'-$uploadfile->getgetClientoriginalName())

            $image = new Image([
                'name' => $name ?: $uploadfile->hashName(),
                'originalName' => $uploadfile->getClientoriginalName(),
                'originalExtension' => $uploadfile->getClientoriginalExtension(),
                'mimeType' => $uploadfile->getClientmimeType(),
                'size' => $uploadfile->getSize(),
                'path' => $path()
            ]);

            $image = Image::create([
                'name' => $request->hashName(),
                'originalName' => $request->getClientoriginalName(),
                'originalExtension' => $request->getClientoriginalExtension(),
                'mimeType' => $request->getClientmimeType(),
                'size' => $request->getSize(),
                'path' => $path()
            ])->create($atributes);
            $destinasi->images()->attach($image);
            $image->save();
            return $image;

        }

        $image = Destinasi::all();
        return $this->fractal(
            app(CreateDestinasiAction::class)->execute($attributes),
            new DestinasiTransformer()
        )
            ->respond(201);
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
                'nama'      => 'required|string',
                'alamat'    => 'required|string',
                'deskripsi' => 'required|string',
                'kota_id'   => 'required|integer',
            ]
        );

        $destinasi = app(FindDestinasiByKeyAction::class)
            ->execute($id);

        $destinasi->update($attributes);

        return $this->fractal($destinasi->refresh(), new DestinasiTransformer());
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

    public function destroy(string $id)
    {
        $destinasi = app(FindDestinasiByKeyAction::class)
            ->execute($id);

        if (app('auth')->id() == $destinasi->getKey()) {
            return response(['message' => 'You cannot delete your self.'], 403);
        }

        $destinasi->delete();

        return response('', 204);
    }
}