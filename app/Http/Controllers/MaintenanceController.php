<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\Infrastructure;
use App\Models\Maintenance;
use App\Models\Technicien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        // 1. Real KPIs from DB
        $statistics = [
            'total' => Maintenance::count(),
            'reported' => Maintenance::where('status', 'reported')->count(),
            'planned' => Maintenance::where('status', 'planned')->where(fn ($q) => $q->whereNull('scheduled_at')->orWhere('scheduled_at', '>=', now()))->count(),
            'in_progress' => Maintenance::where('status', 'in_progress')->count(),
            'completed' => Maintenance::where('status', 'completed')->count(),
            'overdue' => Maintenance::status('overdue')->count(),
            'this_month' => Maintenance::period('this_month')->count(),
        ];

        // 2. Query builder
        $query = Maintenance::with(['infrastructure.zone', 'technician'])
            ->search($request->input('search'))
            ->infrastructure($request->input('infrastructure_id'))
            ->technician($request->input('technician_id'))
            ->type($request->input('type'))
            ->priority($request->input('priority'));

        // Quick filters
        $quick = $request->input('quick');
        if ($quick === 'reported') {
            $query->where('status', 'reported');
        } elseif ($quick === 'planned') {
            $query->where('status', 'planned')->where(fn ($q) => $q->whereNull('scheduled_at')->orWhere('scheduled_at', '>=', now()));
        } elseif ($quick === 'in_progress') {
            $query->where('status', 'in_progress');
        } elseif ($quick === 'completed') {
            $query->where('status', 'completed');
        } elseif ($quick === 'overdue') {
            $query->status('overdue');
        } elseif ($quick === 'today') {
            $query->period('today');
        } elseif ($quick === 'this_week') {
            $query->period('this_week');
        } elseif ($request->filled('status')) {
            $query->status($request->input('status'));
        }

        // Sorting
        $sort = $request->input('sort', 'scheduled_at');
        $order = strtolower($request->input('order', 'desc')) === 'asc' ? 'asc' : 'desc';
        if (in_array($sort, ['scheduled_at', 'priority', 'cost', 'status', 'created_at'], true)) {
            $query->orderBy($sort, $order);
        } else {
            $query->latest('scheduled_at');
        }

        $viewMode = $request->input('view', 'table');
        $maintenances = $query->paginate(10)->withQueryString();

        // 3. Active filter chips
        $activeFilters = $this->buildActiveFilters($request);

        // 4. Options
        $infrastructures = Infrastructure::orderBy('name')->get(['id', 'name', 'reference_code']);
        $technicians = Technicien::orderBy('name')->get(['id', 'name']);
        $types = ['Préventive', 'Corrective', 'Inspection', 'Réglementaire', 'Urgence'];
        $priorities = ['low' => 'Basse', 'medium' => 'Moyenne', 'high' => 'Haute', 'critical' => 'Critique'];
        $statuses = ['planned' => 'Planifiée', 'in_progress' => 'En cours', 'completed' => 'Terminée', 'cancelled' => 'Annulée', 'overdue' => 'En retard'];

        // If calendar / timeline view, get upcoming and recent records
        $calendarItems = [];
        if ($viewMode === 'calendar') {
            $calendarItems = (clone $query)->reorder()->orderBy('scheduled_at', 'asc')->limit(30)->get();
        }

        return view('maintenances.index', compact(
            'maintenances',
            'statistics',
            'infrastructures',
            'technicians',
            'types',
            'priorities',
            'statuses',
            'viewMode',
            'activeFilters',
            'calendarItems'
        ));
    }

    public function create(Request $request): View
    {
        $selectedInfrastructureId = $request->integer('infrastructure_id');

        return view('maintenances.create', array_merge(
            ['selectedInfrastructureId' => $selectedInfrastructureId],
            $this->formOptions()
        ));
    }

    public function store(StoreMaintenanceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Auto reference code
        if (empty($validated['reference_code'])) {
            $nextId = (Maintenance::max('id') ?? 0) + 1;
            $validated['reference_code'] = sprintf('MNT-%s-%03d', date('Y'), $nextId);
        }

        // Completion timestamps
        if ($validated['status'] === 'completed' && empty($validated['completed_at'])) {
            $validated['completed_at'] = now();
            $validated['performed_at'] = now();
        }

        $maintenance = Maintenance::create($validated);

        // Sync with Infrastructure
        $infra = $maintenance->infrastructure;
        if ($infra) {
            if ($maintenance->status === 'completed') {
                $infra->last_maintenance_date = now()->format('Y-m-d');
                if ($infra->status === 'maintenance') {
                    $infra->status = 'operational';
                }
            } elseif ($maintenance->status === 'in_progress') {
                $infra->status = 'maintenance';
            }

            if (!empty($maintenance->next_maintenance_date)) {
                $infra->next_maintenance_date = $maintenance->next_maintenance_date;
            }
            $infra->save();
        }

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', "L'opération de maintenance « {$maintenance->reference_code} » a été planifiée avec succès.");
    }

    public function show(Maintenance $maintenance): View
    {
        $maintenance->load(['infrastructure.zone', 'technician']);

        return view('maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance): View
    {
        return view('maintenances.edit', array_merge(
            ['maintenance' => $maintenance],
            $this->formOptions()
        ));
    }

    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance): RedirectResponse
    {
        $validated = $request->validated();

        if ($validated['status'] === 'completed' && empty($validated['completed_at'])) {
            $validated['completed_at'] = now();
            $validated['performed_at'] = now();
        }

        $maintenance->update($validated);

        // Sync with Infrastructure
        $infra = $maintenance->infrastructure;
        if ($infra) {
            if ($maintenance->status === 'completed') {
                $infra->last_maintenance_date = now()->format('Y-m-d');
                if ($infra->status === 'maintenance') {
                    $infra->status = 'operational';
                }
            } elseif ($maintenance->status === 'in_progress') {
                $infra->status = 'maintenance';
            }

            if (!empty($maintenance->next_maintenance_date)) {
                $infra->next_maintenance_date = $maintenance->next_maintenance_date;
            }
            $infra->save();
        }

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', "La maintenance « {$maintenance->reference_code} » a été mise à jour.");
    }

    public function destroy(Maintenance $maintenance): RedirectResponse
    {
        $ref = $maintenance->reference_code ?? ('#' . $maintenance->id);
        $maintenance->delete();

        return redirect()
            ->route('maintenances.index')
            ->with('success', "La maintenance {$ref} a été supprimée.");
    }

    public function start(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $maintenance->status = 'in_progress';
        $maintenance->started_at = now();

        if ($request->filled('technician_id')) {
            $maintenance->technician_id = $request->integer('technician_id');
        }

        $maintenance->save();

        if ($maintenance->infrastructure) {
            $maintenance->infrastructure->status = 'maintenance';
            $maintenance->infrastructure->save();
        }

        return redirect()
            ->route('maintenances.show', $maintenance)
            ->with('success', "Le signalement {$maintenance->reference_code} a été validé et basculé en cours d'intervention.");
    }

    private function formOptions(): array
    {
        return [
            'infrastructures' => Infrastructure::orderBy('name')->get(['id', 'name', 'reference_code']),
            'technicians' => Technicien::orderBy('name')->get(['id', 'name', 'speciality']),
            'types' => ['Préventive', 'Corrective', 'Inspection', 'Réglementaire', 'Urgence'],
            'priorities' => [
                'low' => 'Basse',
                'medium' => 'Moyenne',
                'high' => 'Haute',
                'critical' => 'Critique',
            ],
            'statuses' => [
                'reported' => 'Signalement usager (En attente)',
                'planned' => 'Planifiée',
                'in_progress' => 'En cours',
                'completed' => 'Terminée',
                'cancelled' => 'Annulée',
            ],
        ];
    }

    private function buildActiveFilters(Request $request): array
    {
        $filters = [];

        if ($request->filled('search')) {
            $filters['search'] = ['label' => 'Recherche : ' . $request->input('search'), 'param' => 'search'];
        }
        if ($request->filled('quick')) {
            $labels = [
                'planned' => 'Statut : Planifiée',
                'in_progress' => 'Statut : En cours',
                'completed' => 'Statut : Terminée',
                'overdue' => 'Statut : En retard',
                'today' => 'Planifiées aujourd’hui',
                'this_week' => 'Planifiées cette semaine',
            ];
            if (isset($labels[$request->input('quick')])) {
                $filters['quick'] = ['label' => $labels[$request->input('quick')], 'param' => 'quick'];
            }
        }
        if ($request->filled('status') && !$request->filled('quick')) {
            $filters['status'] = ['label' => 'Statut : ' . ucfirst($request->input('status')), 'param' => 'status'];
        }
        if ($request->filled('type')) {
            $filters['type'] = ['label' => 'Type : ' . $request->input('type'), 'param' => 'type'];
        }
        if ($request->filled('priority')) {
            $filters['priority'] = ['label' => 'Priorité : ' . ucfirst($request->input('priority')), 'param' => 'priority'];
        }
        if ($request->filled('infrastructure_id')) {
            $infra = Infrastructure::find($request->input('infrastructure_id'));
            if ($infra) {
                $filters['infrastructure_id'] = ['label' => 'Infra : ' . $infra->name, 'param' => 'infrastructure_id'];
            }
        }
        if ($request->filled('technician_id')) {
            $tech = Technicien::find($request->input('technician_id'));
            if ($tech) {
                $filters['technician_id'] = ['label' => 'Technicien : ' . $tech->name, 'param' => 'technician_id'];
            }
        }

        return $filters;
    }
}
