<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Financement;
use App\Models\Incident;
use App\Models\Infrastructure;
use App\Models\Project;
use App\Models\Signalement;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontController extends Controller
{
    private function shared(): array
    {
        return [
            'stats' => [
                'Sites sécurisés' => (string) max(Infrastructure::count(), 128),
                'Alertes traitées' => (string) max(Incident::count() * 120, 2480),
                'Communes partenaires' => (string) max(Zone::count() * 11, 34),
            ]
        ];
    }

    public function home(): View
    {
        return view('front.home', $this->shared() + [
            'featuredProjects' => $this->projectsData(),
            'networkStatus' => [
                ['icon' => '◉', 'label' => "Qualité de l'eau", 'value' => '98.7', 'unit' => '%', 'status' => 'Excellent', 'change' => '+2.4 %', 'tone' => 'success'],
                ['icon' => '↕', 'label' => 'Pression du réseau', 'value' => '4.2', 'unit' => 'bar', 'status' => 'Normal', 'change' => '+0.8 %', 'tone' => 'info'],
                ['icon' => '◒', 'label' => 'Niveau des réservoirs', 'value' => '76', 'unit' => '%', 'status' => 'Attention', 'change' => '-3.1 %', 'tone' => 'warning'],
                ['icon' => '⌁', 'label' => 'Réseau global', 'value' => '99.2', 'unit' => '%', 'status' => 'Opérationnel', 'change' => '+1.2 %', 'tone' => 'success'],
            ],
            'recentIncidents' => $this->incidentsData(),
            'infrastructures' => $this->infrastructureData(),
        ]);
    }

    public function about(): View
    {
        return view('front.about', $this->shared());
    }

    public function services(): View
    {
        return view('front.services', $this->shared());
    }

    public function projects(): View
    {
        return view('front.projects.index', $this->shared() + ['projects' => $this->projectsData()]);
    }

    public function funding(): View
    {
        $programs = Financement::pluck('source')->unique()->toArray();
        if (empty($programs)) {
            $programs = ['Fonds bleu européen', 'Aqua Transition', 'Résilience territoriale'];
        }
        return view('front.funding', $this->shared() + ['programs' => $programs]);
    }

    public function news(): View
    {
        return view('front.news', $this->shared() + [
            'articles' => [
                'AquaSecure déploie son réseau de capteurs nouvelle génération',
                'Un nouveau partenariat pour protéger nos littoraux',
                'Bilan de la saison hydrologique 2025'
            ]
        ]);
    }

    public function contact(): View
    {
        return view('front.contact', $this->shared());
    }

    public function incidents(): View
    {
        return view('front.incidents.index', $this->shared() + ['incidents' => $this->incidentsData()]);
    }

    public function createIncident(): View
    {
        $zones = Zone::all();
        $infrastructures = Infrastructure::all();
        return view('front.incidents.create', $this->shared() + compact('zones', 'infrastructures'));
    }

    public function storeIncident(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'priority' => 'nullable|string|max:50',
            'zone_id' => 'nullable|exists:zones,id',
            'infrastructure_id' => 'nullable|exists:infrastructures,id',
            'incident_date' => 'nullable|date',
            'photo' => 'nullable|image|max:4096',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('incidents', 'public');
        }

        $zone = null;
        if (!empty($validated['zone_id'])) {
            $zone = Zone::find($validated['zone_id']);
        }
        if (!$zone) {
            $zone = Zone::firstOrCreate(
                ['name' => 'Secteur Local'],
                ['address' => $validated['location'], 'risk_level' => 'medium']
            );
        }

        Signalement::create([
            'user_id' => auth()->id(),
            'reporter_name' => auth()->user()->name,
            'reporter_email' => auth()->user()->email,
            'type' => $validated['type'],
            'description' => $validated['description'] . (!empty($validated['title']) ? ' — ' . $validated['title'] : ''),
            'location' => $validated['location'],
            'photo_path' => $photoPath,
            'priority' => $validated['priority'] ?? null,
            'status' => 'new',
            'reported_at' => !empty($validated['incident_date']) ? $validated['incident_date'] : now(),
        ]);

        return redirect()->route('front.incidents.index')->with('success', 'Signalement envoyé. Il sera vérifié par nos équipes.');
    }

    public function showIncident(string $incident): View
    {
        $incidentId = ctype_digit($incident) ? (int) $incident : (int) preg_replace('/\D+/', '', $incident);
        $record = Incident::with(['zone', 'infrastructure', 'signalements'])
            ->when(auth()->user()?->role === 'citizen', fn ($query) => $query->whereHas('signalements', fn ($signalements) => $signalements->where('user_id', auth()->id())))
            ->findOrFail($incidentId);
        $incidentData = [
            'id' => 'INC-' . str_pad($record->id, 4, '0', STR_PAD_LEFT),
            'title' => $record->description,
            'site' => $record->location ?? ($record->zone->name ?? 'Site non défini'),
            'priority' => match ($record->severity) {
                'critical' => 'Critique',
                'high' => 'Élevée',
                'low' => 'Faible',
                default => 'Moyenne',
            },
            'status' => [
                'reported' => 'Ouvert',
                'in_progress' => 'En cours',
                'resolved' => 'Résolu',
                'closed' => 'Fermé',
            ][$record->status] ?? $record->status,
            'date' => $record->reported_at ? $record->reported_at->format('d M Y') : now()->format('d M Y'),
            'description' => $record->description,
            'photo_path' => $record->photo_path,
            'type' => $record->type,
        ];

        return view('front.incidents.show', $this->shared() + ['incident' => $incidentData, 'incidentCode' => $incidentData['id']]);
    }

    public function infrastructures(): View
    {
        return view('front.infrastructures.index', $this->shared() + ['infrastructures' => $this->infrastructureData()]);
    }

    public function showInfrastructure(string $infrastructure): View
    {
        $record = Infrastructure::with('zone')->where('id', $infrastructure)->orWhere('name', $infrastructure)->first();
        $infraData = $record ? [
            'name' => $record->name,
            'type' => $record->type,
            'region' => $record->zone->name ?? 'France',
            'health' => $record->status === 'operational' ? '98%' : ($record->status === 'maintenance' ? '76%' : '45%'),
            'status' => $record->status === 'operational' ? 'Connectée' : 'Maintenance',
        ] : ($this->infrastructureData()[0] ?? []);

        return view('front.infrastructures.show', $this->shared() + ['infrastructure' => $infraData, 'infrastructureCode' => $infrastructure]);
    }

    public function showProject(string $project): View
    {
        $record = Project::where('id', $project)->orWhere('name', $project)->first();
        $projData = $record ? [
            'name' => $record->name,
            'location' => 'Territoire national',
            'status' => $record->status === 'in_progress' ? 'En cours' : ($record->status === 'completed' ? 'Opérationnel' : 'À l’étude'),
            'progress' => $record->progress,
            'type' => $record->type,
        ] : ($this->projectsData()[0] ?? []);

        return view('front.projects.show', $this->shared() + ['project' => $projData, 'projectCode' => $project]);
    }

    private function projectsData(): array
    {
        return Project::all()->map(fn($p) => [
            'name' => $p->name,
            'location' => 'France',
            'status' => $p->status === 'in_progress' ? 'En déploiement' : ($p->status === 'completed' ? 'Opérationnel' : 'À l’étude'),
            'progress' => $p->progress,
            'type' => $p->type,
        ])->toArray();
    }

    private function incidentsData(): array
    {
        return Incident::with('zone')->when(auth()->user()?->role === 'citizen', fn ($query) => $query->whereHas('signalements', fn ($signalements) => $signalements->where('user_id', auth()->id())))->orderByDesc('id')->get()->map(fn($i) => [
            'id' => 'INC-' . str_pad($i->id, 4, '0', STR_PAD_LEFT),
            'title' => $i->description,
            'site' => $i->location ?? ($i->zone->name ?? 'Zone'),
            'priority' => 'Critique',
            'status' => $i->status === 'reported' ? 'Ouvert' : ($i->status === 'in_progress' ? 'En cours' : 'Résolu'),
            'date' => $i->reported_at ? $i->reported_at->format('d M Y') : now()->format('d M Y'),
        ])->toArray();
    }

    private function infrastructureData(): array
    {
        return Infrastructure::with('zone')->get()->map(fn($inf) => [
            'name' => $inf->name,
            'type' => $inf->type,
            'region' => $inf->zone->name ?? 'France',
            'health' => $inf->status === 'operational' ? '98%' : '76%',
            'status' => $inf->status === 'operational' ? 'Connectée' : 'Maintenance',
        ])->toArray();
    }
}
