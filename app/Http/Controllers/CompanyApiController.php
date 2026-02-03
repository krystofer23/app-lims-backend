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
                ->orderBy('id', 'desc') 
                ->paginate(15);

            return $this->sendResponse($data, 'Enviando empresas');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    
    public function store(Request $request): JsonResponse
    {
        try {
            
            $request->validate([
                'ruc'  => 'required|numeric|digits:11|unique:companies,ruc',
                'name' => 'required|string|max:255',
            ]);

            
            $category = $request->category;
            if (is_array($category)) {
                $category = implode(', ', $category);
            }

            
            $company = Company::create([
                'ruc'          => $request->ruc,
                'name'         => $request->name,
                'direction'    => $request->direction,
                'activity'     => $request->activity,
                'category'     => $category, 
                'state'        => $request->state ?? true,
                'is_supplier'  => $request->is_supplier ?? false,
                'is_partner'   => $request->is_partner ?? false,
                'observations' => $request->observations,
            ]);

            return $this->sendResponse($company, 'Empresa creada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    
    public function show($id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);
            return $this->sendResponse($company, 'Datos de la empresa obtenidos');
        } catch (Exception $e) {
            return $this->sendError('Empresa no encontrada', 404);
        }
    }

    
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);

            
            $request->validate([
                'ruc'  => 'required|numeric|digits:11|unique:companies,ruc,' . $company->id,
                'name' => 'required|string|max:255',
            ]);

            
            $data = $request->all();
            if ($request->has('category') && is_array($request->category)) {
                $data['category'] = implode(', ', $request->category);
            }

            
            $company->update($data);

            return $this->sendResponse($company, 'Empresa actualizada con exito');
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }

    
    public function destroy($id): JsonResponse
    {
        try {
            $company = Company::findOrFail($id);
            $company->delete();

            return $this->sendSuccess('Empresa eliminada correctamente');
        } catch (Exception $e) {
            return $this->sendError('No se pudo eliminar la empresa', 404);
        }
    }
}