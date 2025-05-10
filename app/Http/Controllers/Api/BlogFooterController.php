<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BlogFooter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BlogFooterController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'public_image1' => 'required|string',
                'url_image1' => 'nullable|string',
                'public_image2' => 'required|string',
                'url_image2' => 'nullable|string',
                'public_image3' => 'required|string',
                'url_image3' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            DB::beginTransaction();

            $blogFooter = BlogFooter::create($request->all());

            DB::commit();

            return response()->json([
                "status" => 200,
                "message" => "BlogFooter creado correctamente",
                "id" => $blogFooter->id_blog_footer
            ], 200);

        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                "status" => 500,
                "message" => "Error interno del servidor",
                "error" => $ex->getMessage()
                ], 500);
        }
    }

    public function update(Request $request, int $id)
    {
        try{
            $validator = Validator::make($request->all(), [
                'titulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'public_image1' => 'required|string',
                'url_image1' => 'nullable|string',
                'public_image2' => 'required|string',
                'url_image2' => 'nullable|string',
                'public_image3' => 'required|string',
                'url_image3' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors'=> $validator->errors()], 400);
            }

            $blogFooter = BlogFooter::find($id);

            if (!$blogFooter){
                return response()->json([
                    'status'=> 404,
                    'message'=> 'BlogFooter no encontrado'
                ], 404);
            }

            DB::beginTransaction();

            $blogFooter->update($request->all());

            DB::commit();
            return response()->json([
                'status'=> 200,
                'message'=> 'BlogFooter actualizado',
                'id'=> $blogFooter->id_blog_footer,
            ],200);

        }catch(\Exception $ex){
            DB::rollback();
            return response()->json([
                'status'=> 500,
                'message'=> $ex->getMessage()
            ], 500);
        }
    }

    public function show(int $id){
        try{

            $blogFooter = BlogFooter::find($id);
            if (!$blogFooter) {
                return response()->json([
                    "status" => 404,
                    "message" => "BlogFooter no encontrado"
                ],404);
            }

            return response()->json([
                "status" => 200,
                "data" => $blogFooter
                ], 200);

        }catch(\Exception $ex){
            return response()->json([
                "status" => 500,
                "message" => "Error interno del servidor",
                "error" => $ex->getMessage()
                ], 500);
        }
    }

    public function destroy($id)
    {
        try{

            $blogFooter = BlogFooter::find($id);

            if (!$blogFooter) {
                return response()->json([
                    "status" => 404,
                    "message" => "BlogFooter no encontrado"
                ]);
            }
            $blogFooter->delete();
            return response()->json([
                "status" => 200,
                "message" => "BlogFooter eliminado correctamente"
                ], 200);

        }catch(\Exception $ex){
            return response()->json([
                "status" => 500,
                "message" => "Error al eliminar el blogFooter",
                "error" => $ex->getMessage()
                ], 500);
        }
    }
}
