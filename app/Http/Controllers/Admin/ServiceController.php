<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('services/Index', [
            'services' => Service::query()
                ->with('serviceCategory:id,name')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('services/Create', [
            'serviceCategories' => $this->activeCategories(),
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        Service::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Layanan berhasil dibuat.']);

        return to_route('admin.services.index');
    }

    public function edit(Service $service): Response
    {
        return Inertia::render('services/Edit', [
            'service' => $service,
            'serviceCategories' => $this->activeCategories(),
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Layanan berhasil diperbarui.']);

        return to_route('admin.services.index');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Layanan berhasil dihapus.']);

        return to_route('admin.services.index');
    }

    /**
     * @return Collection<int, array{id: int, name: string}>
     */
    private function activeCategories(): Collection
    {
        return ServiceCategory::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (ServiceCategory $category) => [
                'id' => $category->id,
                'name' => $category->name,
            ]);
    }
}
