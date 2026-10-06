<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Models\Financement;
use App\Models\Incident;
use App\Models\Infrastructure;
use App\Models\Project;
use App\Models\User;
use Illuminate\View\View;

class BackController extends Controller
{
    public function dashboard(): View
    {
        $infraCount = Infrastructure::count();
        $activeIncidents = Incident::where('status', '!=', 'resolved')->where('status', '!=', 'closed')->count();
        $criticalInfras = Infrastructure::where('status', '!=', 'operational')->count();
        $budgetTotal = (float) Project::sum('budget');
        $fundingTotal = (float) Financement::sum('amount');
        $projectCount = Project::count();
        $inProgressProjects = Project::where('status', 'in_progress')->count();
        $completedProjects = Project::where('status', 'completed')->count();

        $kpis = [
            ['label' => 'Sites surveillés', 'value' => (string) $infraCount, 'trend' => 'Données réelles'],
            ['label' => 'Incidents actifs', 'value' => sprintf('%02d', $activeIncidents), 'trend' => 'Données réelles'],
            ['label' => 'Infrastructures critiques', 'value' => sprintf('%02d', $criticalInfras), 'trend' => 'Données réelles'],
            ['label' => 'Projets actifs', 'value' => (string) $inProgressProjects, 'trend' => 'Données réelles'],
            ['label' => 'Budget total', 'value' => number_format($budgetTotal, 0, ',', ' ') . ' €', 'trend' => number_format($fundingTotal, 0, ',', ' ') . ' € financés'],
        ];

        $activity = [
            'Capteur #A-204 reconnecté',
            'Nouvel incident signalé à Sète',
            'Validation du projet Adour-Garonne',
        ];

        $projectStats = [
            'total' => $projectCount,
            'in_progress' => $inProgressProjects,
            'completed' => $completedProjects,
            'budget' => $budgetTotal,
            'funding' => $fundingTotal,
            'remaining' => max(0, $budgetTotal - $fundingTotal),
        ];

        return view('back.dashboard', compact('kpis', 'activity', 'projectStats'));
    }

    public function incidents(): View
    {
        $incidents = Incident::with('zone')->orderByDesc('id')->get()->map(function ($item) {
            $statusLabels = [
                'reported' => 'Ouvert',
                'in_progress' => 'En cours',
                'resolved' => 'Résolu',
                'closed' => 'Fermé',
            ];

            return [
                'id' => 'INC-' . str_pad($item->id, 4, '0', STR_PAD_LEFT),
                'title' => $item->description,
                'site' => $item->location ?? ($item->zone->name ?? 'Zone'),
                'priority' => $item->type === 'Contamination' ? 'Critique' : 'Moyenne',
                'status' => $statusLabels[$item->status] ?? $item->status,
                'date' => $item->reported_at ? $item->reported_at->format('d M Y') : now()->format('d M Y'),
            ];
        });

        return view('back.incidents.index', compact('incidents'));
    }

    public function infrastructures(): View
    {
        $items = Infrastructure::with('zone')->get()->map(function ($item) {
            return [
                'name' => $item->name,
                'type' => $item->type,
                'region' => $item->zone->name ?? 'France',
                'health' => $item->status === 'operational' ? '98%' : ($item->status === 'maintenance' ? '76%' : '45%'),
                'status' => $item->status === 'operational' ? 'Connectée' : 'Maintenance',
            ];
        });

        return view('back.infrastructures.index', compact('items'));
    }

    public function projects(): View
    {
        $projects = Project::all()->map(function ($item) {
            $budgetFormatted = number_format($item->budget / 1000000, 1, ',', ' ') . ' M€';
            if ($item->budget < 1000000) {
                $budgetFormatted = number_format($item->budget / 1000, 0, ',', ' ') . ' k€';
            }

            return [
                'name' => $item->name,
                'owner' => 'Équipe AquaSecure',
                'budget' => $budgetFormatted,
                'progress' => $item->progress,
                'status' => $item->status === 'in_progress' ? 'En cours' : ($item->status === 'completed' ? 'Opérationnel' : 'À l’étude'),
            ];
        });

        return view('back.projects.index', compact('projects'));
    }

    public function funding(): View
    {
        $funding = Financement::with('project')->get()->map(function ($item) {
            $amountFormatted = number_format($item->amount / 1000000, 1, ',', ' ') . ' M€';
            if ($item->amount < 1000000) {
                $amountFormatted = number_format($item->amount / 1000, 0, ',', ' ') . ' k€';
            }

            return [
                'name' => $item->source,
                'amount' => $amountFormatted,
                'used' => $item->status === 'received' ? '68%' : '20%',
                'deadline' => $item->funded_at ? $item->funded_at->format('d M Y') : '31 déc. 2026',
                'status' => $item->status === 'received' ? 'Actif' : 'Clôturé',
            ];
        });

        return view('back.funding.index', compact('funding'));
    }

    public function users(): View
    {
        $roleLabels = [
            'admin' => 'Administratrice',
            'manager' => 'Chef de projet',
            'technician' => 'Technicienne',
            'citizen' => 'Citoyen',
        ];

        $users = User::all()->map(function ($item) use ($roleLabels) {
            return [
                'name' => $item->name,
                'email' => $item->email,
                'role' => $roleLabels[$item->role] ?? ucfirst($item->role),
                'last' => 'Aujourd’hui',
                'state' => 'Actif',
            ];
        });

        return view('back.users.index', compact('users'));
    }
}
