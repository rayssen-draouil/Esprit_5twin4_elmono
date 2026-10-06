<?php

namespace Tests\Feature;

use App\Models\Alert;
use App\Models\Incident;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AlertTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_alert_list_loads(): void
    {
        Alert::factory()->create(['type' => 'Fuite', 'message' => 'Fuite détectée sur le secteur nord.']);

        $response = $this->get(route('back.alerts.index'));

        $response->assertOk()
            ->assertSee('Alertes')
            ->assertSee('Fuite')
            ->assertSee('Fuite détectée sur le secteur nord.');
    }

    public function test_store_with_valid_data_creates_alert(): void
    {
        $zone = Zone::factory()->create();

        $response = $this->post(route('back.alerts.store'), [
            'zone_id' => $zone->id,
            'incident_id' => null,
            'type' => 'Contamination',
            'severity' => 'critical',
            'message' => 'Dépassement de turbidité détecté.',
        ]);

        $response->assertRedirect(route('back.alerts.index'));
        $this->assertDatabaseHas('alerts', [
            'zone_id' => $zone->id,
            'type' => 'Contamination',
            'severity' => 'critical',
            'message' => 'Dépassement de turbidité détecté.',
        ]);
    }

    public function test_store_with_invalid_data_returns_errors(): void
    {
        $response = $this->from(route('back.alerts.create'))
            ->post(route('back.alerts.store'), [
                'zone_id' => 999,
                'incident_id' => 999,
                'type' => '',
                'severity' => 'ultra',
                'message' => '',
            ]);

        $response->assertSessionHasErrors(['zone_id', 'incident_id', 'type', 'severity', 'message']);

        $errors = session('errors')->toArray();
        $this->assertSame('Cette zone n’existe pas.', $errors['zone_id'][0]);
        $this->assertSame('Gravité invalide.', $errors['severity'][0]);

        $this->assertDatabaseCount('alerts', 0);
    }

    public function test_update_modifies_alert_and_marks_it_read(): void
    {
        $zone = Zone::factory()->create();
        $alert = Alert::factory()->create([
            'zone_id' => $zone->id,
            'severity' => 'low',
            'read_at' => null,
        ]);

        $response = $this->put(route('back.alerts.update', $alert), [
            'zone_id' => $zone->id,
            'incident_id' => null,
            'type' => 'Risque élevé',
            'severity' => 'critical',
            'message' => 'Message mis à jour.',
            'read' => 1,
        ]);

        $response->assertRedirect(route('back.alerts.show', $alert));
        $this->assertDatabaseHas('alerts', [
            'id' => $alert->id,
            'type' => 'Risque élevé',
            'severity' => 'critical',
            'message' => 'Message mis à jour.',
        ]);
        $this->assertNotNull($alert->fresh()->read_at);
    }

    public function test_delete_removes_alert(): void
    {
        $alert = Alert::factory()->create();

        $response = $this->delete(route('back.alerts.destroy', $alert));

        $response->assertRedirect(route('back.alerts.index'));
        $this->assertDatabaseMissing('alerts', ['id' => $alert->id]);
    }

    public function test_zone_show_page_lists_its_alerts(): void
    {
        $zone = Zone::factory()->create(['risk_level' => 'low']);
        $otherZone = Zone::factory()->create(['risk_level' => 'low']);

        $linked = Alert::factory()->create([
            'zone_id' => $zone->id,
            'type' => 'Risque élevé',
            'message' => 'Alerte spécifique à la zone testée.',
        ]);
        Alert::factory()->create([
            'zone_id' => $otherZone->id,
            'type' => 'Fuite',
            'message' => 'Alerte d une autre zone.',
        ]);

        $response = $this->get(route('back.zones.show', $zone));

        $response->assertOk()
            ->assertSee('Risque élevé')
            ->assertSee('Alerte spécifique à la zone testée.')
            ->assertDontSee('Alerte d une autre zone.');

        $this->assertTrue($zone->alerts->contains('id', $linked->id));
        $this->assertSame(1, $zone->alerts()->count());
    }

    public function test_front_alerts_page_loads_with_alert_data(): void
    {
        Alert::factory()->create([
            'type' => 'Fuite',
            'message' => 'Fuite majeure signalée sur le front.',
        ]);

        $response = $this->get('/alerts');

        $response->assertOk()
            ->assertSee('Fuite')
            ->assertSee('Fuite majeure signalée sur le front.')
            ->assertSee('Gravité');

        $alias = $this->get('/alertes');
        $alias->assertOk()->assertSee('Fuite majeure signalée sur le front.');
    }

    public function test_alerts_are_observable_when_incident_is_created(): void
    {
        $zone = Zone::factory()->create(['risk_level' => 'low']);
        $incident = Incident::factory()->create([
            'zone_id' => $zone->id,
            'type' => 'Coupure d\'eau',
        ]);

        $this->assertDatabaseHas('alerts', [
            'zone_id' => $zone->id,
            'incident_id' => $incident->id,
            'type' => "Coupure d'eau",
        ]);
    }
}
