<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WorksheetStoreRequest;
use App\Http\Requests\Admin\WorksheetUpdateRequest;
use App\Http\Resources\Admin\PageTypeResource;
use App\Http\Resources\Admin\WorksheetResource;
use App\Models\Worksheet;
use App\Services\Admin\PageTypeService;
use App\Services\Admin\WorksheetService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class WorksheetController extends Controller
{
    public function __construct(
        private readonly WorksheetService $worksheetService,
        private readonly PageTypeService $pageTypeService,
    ) {
    }

    public function index(): Response
    {
        $worksheets = $this->worksheetService->paginate();

        return Inertia::render('Admin/Worksheets/Index', [
            'worksheets' => $this->formatPaginator($worksheets),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Worksheets/Create', [
            'pageTypes' => PageTypeResource::collection($this->pageTypeService->all())->resolve(),
        ]);
    }

    public function store(WorksheetStoreRequest $request): JsonResponse|RedirectResponse
    {
        $worksheet = $this->worksheetService->store($request->validated());

        return response()->api(__('Worksheet created successfully.'), meta: [
            'redirectTo' => static fn (): RedirectResponse => Redirect::route('admin.worksheets.show', $worksheet),
        ]);
    }

    public function show(Worksheet $worksheet): Response
    {
        $worksheet = $this->worksheetService->find($worksheet->getKey());

        return Inertia::render('Admin/Worksheets/Show', [
            'worksheet' => WorksheetResource::make($worksheet)->resolve(),
        ]);
    }

    public function edit(Worksheet $worksheet): Response
    {
        $worksheet = $this->worksheetService->find($worksheet->getKey());

        return Inertia::render('Admin/Worksheets/Edit', [
            'worksheet' => WorksheetResource::make($worksheet)->resolve(),
        ]);
    }

    public function update(WorksheetUpdateRequest $request, Worksheet $worksheet): JsonResponse|RedirectResponse
    {
        $worksheet = $this->worksheetService->update(
            $this->worksheetService->find($worksheet->getKey()),
            $request->validated(),
        );

        return response()->api(__('Worksheet updated successfully.'), meta: [
            'redirectTo' => static fn (): RedirectResponse => Redirect::route('admin.worksheets.edit', $worksheet),
        ]);
    }

    public function destroy(Worksheet $worksheet): JsonResponse|RedirectResponse
    {
        $this->worksheetService->delete(
            $this->worksheetService->find($worksheet->getKey())
        );

        return response()->api(__('Worksheet deleted successfully.'), meta: [
            'redirectTo' => static fn (): RedirectResponse => Redirect::route('admin.worksheets.index'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => WorksheetResource::collection($paginator->items())->resolve(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];
    }
}
