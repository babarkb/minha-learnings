<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Admin\PageTypeService;
use App\Services\Admin\WorksheetService;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function __construct(
        private readonly PageTypeService $pageTypeService,
        private readonly WorksheetService $worksheetService,
    ) {
    }

    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'metrics' => [
                'page_types' => $this->pageTypeService->count(),
                'worksheets' => $this->worksheetService->count(),
            ],
        ]);
    }
}
