<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CounterRequest;
use App\Models\Counter;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class CounterController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('counters/Index', [
            'counters' => Counter::query()
                ->with('service:id,name,prefix')
                ->orderByDesc('id')
                ->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('counters/Create', [
            'services' => $this->activeServices(),
        ]);
    }

    public function store(CounterRequest $request): RedirectResponse
    {
        Counter::query()->create($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Loket berhasil dibuat.']);

        return to_route('admin.counters.index');
    }

    public function edit(Counter $counter): Response
    {
        return Inertia::render('counters/Edit', [
            'counter' => $counter,
            'services' => $this->activeServices(),
        ]);
    }

    public function update(CounterRequest $request, Counter $counter): RedirectResponse
    {
        $counter->update($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Loket berhasil diperbarui.']);

        return to_route('admin.counters.index');
    }

    public function destroy(Counter $counter): RedirectResponse
    {
        $counter->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Loket berhasil dihapus.']);

        return to_route('admin.counters.index');
    }

    /**
     * @return Collection<int, array{id: int, name: string}>
     */
    private function activeServices(): Collection
    {
        return Service::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(fn (Service $service) => [
                'id' => $service->id,
                'name' => $service->name,
            ]);
    }
}
