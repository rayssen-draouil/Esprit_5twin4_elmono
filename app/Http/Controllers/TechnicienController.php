<?php

namespace App\Http\Controllers;

use App\Models\Technicien;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnicienController extends Controller
{
    public function index(Request $request): View
    {
        $technicians = Technicien::withCount('interventions')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = '%' . $request->input('search') . '%';
                $query->where(function ($builder) use ($search) {
                    $builder->where('name', 'like', $search)
                        ->orWhere('email', 'like', $search)
                        ->orWhere('speciality', 'like', $search);
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->input('status')))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('techniciens.index', [
            'technicians' => $technicians,
            'statuses' => ['available', 'busy', 'inactive'],
        ]);
    }

    public function create(): View
    {
        return view('techniciens.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $technician = Technicien::create($request->validate($this->rules()));

        return redirect()->route('techniciens.show', $technician)->with('success', 'Technicien ajouté avec succès.');
    }

    public function show(Technicien $technicien): View
    {
        return view('techniciens.show', [
            'technician' => $technicien->load(['interventions.incident']),
        ]);
    }

    public function edit(Technicien $technicien): View
    {
        return view('techniciens.edit', ['technician' => $technicien]);
    }

    public function update(Request $request, Technicien $technicien): RedirectResponse
    {
        $technicien->update($request->validate($this->rules()));

        return redirect()->route('techniciens.show', $technicien)->with('success', 'Technicien modifié avec succès.');
    }

    public function destroy(Technicien $technicien): RedirectResponse
    {
        $technicien->delete();

        return redirect()->route('techniciens.index')->with('success', 'Technicien supprimé. Les interventions associées restent sans technicien.');
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'speciality' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:available,busy,inactive'],
        ];
    }
}