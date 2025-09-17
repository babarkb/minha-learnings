<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageTypeStoreRequest;
use App\Http\Requests\Admin\PageTypeUpdateRequest;
use App\Http\Resources\Admin\PageTypeResource;
use App\Models\PageType;
use App\Services\Admin\PageTypeService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PageTypeController extends Controller
{
    public function __construct(
        private readonly PageTypeService $pageTypeService,
    ) {
    }

    public function index(): Response
    {
        $pageTypes = $this->pageTypeService->paginate();

        return Inertia::render('Admin/PageTypes/Index', [
            'pageTypes' => $this->formatPaginator($pageTypes),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/PageTypes/Create');
    }

    public function store(PageTypeStoreRequest $request): JsonResponse|RedirectResponse
    {
        $pageType = $this->pageTypeService->store($request->validated());

        return response()->api(__('Page type created successfully.'), meta: [
            'redirectTo' => static fn (): RedirectResponse => Redirect::route('admin.page-types.edit', $pageType),
        ]);
    }

    public function edit(PageType $pageType): Response
    {
        $pageType = $this->pageTypeService->find($pageType->getKey());

        return Inertia::render('Admin/PageTypes/Edit', [
            'pageType' => PageTypeResource::make($pageType)->resolve(),
        ]);
    }

    public function update(PageTypeUpdateRequest $request, PageType $pageType): JsonResponse|RedirectResponse
    {
        $pageType = $this->pageTypeService->update(
            $this->pageTypeService->find($pageType->getKey()),
            $request->validated(),
        );

        return response()->api(__('Page type updated successfully.'), meta: [
            'redirectTo' => static fn (): RedirectResponse => Redirect::route('admin.page-types.edit', $pageType),
        ]);
    }

    public function destroy(PageType $pageType): JsonResponse|RedirectResponse
    {
        $this->pageTypeService->delete(
            $this->pageTypeService->find($pageType->getKey())
        );

        return response()->api(__('Page type deleted successfully.'), meta: [
            'redirectTo' => static fn (): RedirectResponse => Redirect::route('admin.page-types.index'),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function formatPaginator(LengthAwarePaginator $paginator): array
    {
        return [
            'data' => PageTypeResource::collection($paginator->items())->resolve(),
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
