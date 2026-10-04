<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FrontController extends Controller
{
    private function shared(): array
    {
        return ['stats' => ['Sites sécurisés' => '128', 'Alertes traitées' => '2 480', 'Communes partenaires' => '34']];
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
    public function about(): View { return view('front.about', $this->shared()); }
    public function services(): View { return view('front.services', $this->shared()); }
    public function projects(): View { return view('front.projects.index', $this->shared() + ['projects' => $this->projectsData()]); }
    public function funding(): View { return view('front.funding', $this->shared() + ['programs' => ['Fonds bleu européen', 'Aqua Transition', 'Résilience territoriale']]); }
    public function news(): View { return view('front.news', $this->shared() + ['articles' => ['AquaSecure déploie son réseau de capteurs nouvelle génération', 'Un nouveau partenariat pour protéger nos littoraux', 'Bilan de la saison hydrologique 2025']]); }
    public function contact(): View { return view('front.contact', $this->shared()); }
    public function incidents(): View { return view('front.incidents.index', $this->shared() + ['incidents' => $this->incidentsData()]); }
    public function createIncident(): View { return view('front.incidents.create', $this->shared()); }
    public function showIncident(string $incident): View { return view('front.incidents.show', $this->shared() + ['incident' => $this->incidentsData()[0], 'incidentCode' => $incident]); }
    public function infrastructures(): View { return view('front.infrastructures.index', $this->shared() + ['infrastructures' => $this->infrastructureData()]); }
    public function showInfrastructure(string $infrastructure): View { return view('front.infrastructures.show', $this->shared() + ['infrastructure' => $this->infrastructureData()[0], 'infrastructureCode' => $infrastructure]); }
    public function showProject(string $project): View { return view('front.projects.show', $this->shared() + ['project' => $this->projectsData()[0], 'projectCode' => $project]); }

    private function projectsData(): array
    {
        return [
            ['name' => 'Littoral Méditerranée', 'location' => 'Occitanie', 'status' => 'Opérationnel', 'progress' => 92, 'type' => 'Surveillance côtière'],
            ['name' => 'Vallée du Rhône', 'location' => 'Auvergne-Rhône-Alpes', 'status' => 'En déploiement', 'progress' => 64, 'type' => 'Prévention inondation'],
            ['name' => 'Bassin Adour-Garonne', 'location' => 'Nouvelle-Aquitaine', 'status' => 'À l’étude', 'progress' => 28, 'type' => 'Qualité de l’eau'],
        ];
    }

    private function incidentsData(): array
    {
        return [
            ['id' => 'INC-2048', 'title' => 'Niveau critique — station de pompage', 'site' => 'Littoral Méditerranée', 'priority' => 'Critique', 'status' => 'Ouvert', 'date' => '04 oct. 2026'],
            ['id' => 'INC-2047', 'title' => 'Capteur de turbidité hors ligne', 'site' => 'Vallée du Rhône', 'priority' => 'Moyenne', 'status' => 'En cours', 'date' => '03 oct. 2026'],
        ];
    }

    private function infrastructureData(): array
    {
        return [
            ['name' => 'Station Sète Nord', 'type' => 'Station de pompage', 'region' => 'Occitanie', 'health' => '98%', 'status' => 'Connectée'],
            ['name' => 'Barrage de Pierre-Bénite', 'type' => 'Barrage', 'region' => 'Rhône', 'health' => '94%', 'status' => 'Connectée'],
        ];
    }
}
