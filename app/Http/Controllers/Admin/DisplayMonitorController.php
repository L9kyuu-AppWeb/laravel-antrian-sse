<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DisplayMonitorRequest;
use App\Models\DisplayMonitor;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class DisplayMonitorController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('display-monitors/Index', [
            'displayMonitors' => DisplayMonitor::query()
                ->with('services:id,name,prefix')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('display-monitors/Create', [
            'services' => $this->activeServices(),
        ]);
    }

    public function store(DisplayMonitorRequest $request): RedirectResponse
    {
        $display = DisplayMonitor::query()->create($request->safe()->except('services'));

        $display->services()->sync($request->input('services'));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Display monitor berhasil dibuat.']);

        return to_route('admin.display-monitors.index');
    }

    public function edit(DisplayMonitor $displayMonitor): Response
    {
        return Inertia::render('display-monitors/Edit', [
            'displayMonitor' => $displayMonitor->load('services:id,name'),
            'services' => $this->activeServices(),
        ]);
    }

    public function update(DisplayMonitorRequest $request, DisplayMonitor $displayMonitor): RedirectResponse
    {
        $displayMonitor->update($request->safe()->except('services'));

        $displayMonitor->services()->sync($request->input('services'));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Display monitor berhasil diperbarui.']);

        return to_route('admin.display-monitors.index');
    }

    public function destroy(DisplayMonitor $displayMonitor): RedirectResponse
    {
        $displayMonitor->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Display monitor berhasil dihapus.']);

        return to_route('admin.display-monitors.index');
    }

    /**
     * @return Collection<int, array{id: int, name: string, prefix: string}>
     */
    private function activeServices(): Collection
    {
        return Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'prefix'])
            ->map(fn (Service $service) => [
                'id' => $service->id,
                'name' => $service->name,
                'prefix' => $service->prefix,
            ]);
    }
}
