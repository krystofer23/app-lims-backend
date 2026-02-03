<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\PatientCompany;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function show($id): JsonResponse
    {
        try {
            $company = Company::query()
                ->with([
                    'contacts.user'
                ])
                ->findOrFail($id);

            return $this->sendResponse($company, 'Datos de la empresa obtenidos');
        } catch (Exception $e) {
            return $this->sendError('Empresa no encontrada', 404);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $contacts = $request->contacts ?? [];

            // Creamos la empresa
            $company = Company::create([
                'ruc'          => $request?->ruc,
                'name'         => $request?->name,
                'direction'    => $request?->direction,
                'activity'     => $request?->activity,
                'category'     => $request?->category,
                'state'        => $request?->state ?? true,
                'is_supplier'  => $request?->is_supplier ?? false,
                'is_partner'   => $request?->is_partner ?? false,
                'observations' => $request?->observations,
            ]);

            // Creamos los contactos si esq tiene
            if (is_array($contacts) && count($contacts) !== 0) {
                foreach ($contacts as $key => $value) {
                    $user = User::create([
                        'name' => $value['name'] ?? null,
                        'last_name_first' => $value['last_name_first'] ?? null,
                        'last_name_second' => $value['last_name_second'] ?? null,
                        'document_number' => $value['document_number'] ?? null,
                        'type_document' => $value['type_document'] ?? null,
                        'email' => $value['email'] ?? null,
                        'password' => $value['password'] ?? null,
                    ]);

                    PatientCompany::create([
                        'user_id' => $user?->id,
                        'company_id' => $company?->id,
                        'email' => $value['email'] ?? null,
                        'phone' => $value['phone'] ?? null,
                        'roles' => $value['roles'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return $this->sendResponse($company->id, 'Empresa creada con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);

            $company->update([
                'ruc'          => $request?->ruc,
                'name'         => $request?->name,
                'direction'    => $request?->direction,
                'activity'     => $request?->activity,
                'category'     => $request?->category,
                'state'        => $request?->state ?? true,
                'is_supplier'  => $request?->is_supplier ?? false,
                'is_partner'   => $request?->is_partner ?? false,
                'observations' => $request?->observations,
            ]);

            $contacts = $request->contacts ?? [];

            if (is_array($contacts) && count($contacts) !== 0) {
                foreach ($contacts as $key => $value) {
                    $patient = PatientCompany::find($value['id']);
                    $user = $patient->user;

                    $patient->update([
                        'email' => $value['email'] ?? null,
                        'phone' => $value['phone'] ?? null,
                        'roles' => $value['roles'] ?? null,
                    ]);

                    $user->update([
                        'name' => $value['name'] ?? null,
                        'last_name_first' => $value['last_name_first'] ?? null,
                        'last_name_second' => $value['last_name_second'] ?? null,
                        'document_number' => $value['document_number'] ?? null,
                        'type_document' => $value['type_document'] ?? null,
                        'email' => $value['email'] ?? null,
                    ]);
                }
            }

            DB::commit();
            return $this->sendSuccess('Empresa actualizada con exito');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage(), 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            DB::beginTransaction();

            $company = Company::findOrFail($id);
            $company->contacts->delete();
            $company->delete();

            DB::commit();
            return $this->sendSuccess('Empresa eliminada correctamente');
        } catch (Exception $e) {
            DB::rollBack();
            return $this->sendError('No se pudo eliminar la empresa', 404);
        }
    }
}
