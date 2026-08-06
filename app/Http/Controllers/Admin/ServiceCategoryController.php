<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ServiceCategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('service-categories/Index', [
            'serviceCategories' => ServiceCategory::query()->orderByDesc('id')->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('service-categories/Create');
    }

    public function store(ServiceCategoryRequest $request): RedirectResponse
    {
        ServiceCategory::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Kategori berhasil dibuat.']);

        return to_route('admin.service-categories.index');
    }

    public function edit(ServiceCategory $serviceCategory): Response
    {
        return Inertia::render('service-categories/Edit', [
            'serviceCategory' => $serviceCategory,
        ]);
    }

    public function update(ServiceCategoryRequest $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Kategori berhasil diperbarui.']);

        return to_route('admin.service-categories.index');
    }

    public function destroy(ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Kategori berhasil dihapus.']);

        return to_route('admin.service-categories.index');
    }
}
