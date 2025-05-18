<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * @OA\Info(
 *     title="Agenda de Contatos API",
 *     version="1.0.0",
 *     description="Documentação da API de contatos criada com Laravel e Swagger"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Laravel local"
 * )
 */
class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * @OA\Get(
     *     path="/api/contacts",
     *     tags={"Contatos"},
     *     summary="Listar contatos",
     *     @OA\Response(response=200, description="Lista de contatos paginada")
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json($this->contactService->list());
    }

    /**
     * @OA\Post(
     *     path="/api/contacts",
     *     tags={"Contatos"},
     *     summary="Criar novo contato",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreContactRequest")
     *     ),
     *     @OA\Response(response=201, description="Contato criado com sucesso")
     * )
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = $this->contactService->create($request->validated());
        return response()->json($contact, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/contacts/{id}",
     *     tags={"Contatos"},
     *     summary="Visualizar um contato específico",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Dados do contato")
     * )
     */
    public function show(Contact $contact): JsonResponse
    {
        return response()->json($contact);
    }

    /**
     * @OA\Put(
     *     path="/api/contacts/{id}",
     *     tags={"Contatos"},
     *     summary="Atualizar um contato",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateContactRequest")
     *     ),
     *     @OA\Response(response=200, description="Contato atualizado com sucesso")
     * )
     */
    public function update(UpdateContactRequest $request, Contact $contact): JsonResponse
    {
        $updated = $this->contactService->update($contact, $request->validated());
        return response()->json($updated);
    }

    /**
     * @OA\Delete(
     *     path="/api/contacts/{id}",
     *     tags={"Contatos"},
     *     summary="Excluir um contato",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Contato removido com sucesso")
     * )
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $this->contactService->delete($contact);
        return response()->json(null, 204);
    }
}
