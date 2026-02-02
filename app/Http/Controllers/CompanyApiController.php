<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            $search = $request->input('search');

            $data = Company::query()
                ->when($request->filled('search'), function ($q) use ($search) {
                    $q->where('ruc', 'like', "%{$search}%")
                        ->orWhere('name', 'like', "%{$search}%");
                })
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando empresas');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
