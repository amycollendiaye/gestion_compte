<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{

    protected  function successResponse(string $message, $data=null, int $code = 200):JsonResponse{

         return response()->json(
            [
                'success'=>true,
                'message'=>$message,
                "data"=>$data,

            ],
            $code
        );
    }
     protected function errorResponse(
        string $message = 'Erreur serveur',
        int $code = 500,
        $errors = null
    ):JsonResponse {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
     protected function paginatedResponse($paginator, string $message = 'Liste paginée récupérée avec succès'): JsonResponse
    {
        
            $metadonne= [
                
                'date_creation'=>now()->toIso8601String(),
                'version'=>1
                
                ];

                
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
              "metadonnees"=>$metadonne,
            'pagination' => [
                'currentPage' => $paginator->currentPage(),
                'totalPages' => $paginator->lastPage(),
                'totalItems' => $paginator->total(),
                'itemsPerPage' => $paginator->perPage(),
                'hasNext' => $paginator->hasMorePages(),
                'hasPrevious' => $paginator->currentPage() > 1,
            ],
            'links' => [
                'self' => url()->current() . '?page=' . $paginator->currentPage(),
                'next' => $paginator->nextPageUrl(),
                'prev' => $paginator->previousPageUrl(),
                'first' => url()->current() . '?page=1',
                'last' => url()->current() . '?page=' . $paginator->lastPage(),
            ]
        ], 200);
    }
}
