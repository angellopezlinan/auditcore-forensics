<?php

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Team;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VaultControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_member_can_download_invoice_from_private_vault(): void
    {
        Storage::fake('private');

        [$user, $team] = $this->createUserWithTeam();
        $vendor = Vendor::factory()->create(['team_id' => $team->id]);
        $invoice = Invoice::factory()->create([
            'team_id' => $team->id,
            'vendor_id' => $vendor->id,
            'invoice_number' => 'INV-1001',
            'file_path' => 'invoices/invoice-1001.pdf',
        ]);

        Storage::disk('private')->put($invoice->file_path, 'pdf-content');

        $this->actingAs($user)
            ->get(route('vault.invoices.download', $invoice))
            ->assertOk()
            ->assertDownload('Factura_INV-1001.pdf');
    }

    public function test_download_returns_404_when_the_file_does_not_exist(): void
    {
        Storage::fake('private');

        [$user, $team] = $this->createUserWithTeam();
        $vendor = Vendor::factory()->create(['team_id' => $team->id]);
        $invoice = Invoice::factory()->create([
            'team_id' => $team->id,
            'vendor_id' => $vendor->id,
            'file_path' => 'invoices/missing.pdf',
        ]);

        $this->actingAs($user)
            ->get(route('vault.invoices.download', $invoice))
            ->assertNotFound();
    }

    public function test_user_cannot_download_invoice_from_another_team(): void
    {
        Storage::fake('private');

        [$user] = $this->createUserWithTeam();
        $otherTeam = Team::factory()->create();
        $vendor = Vendor::factory()->create(['team_id' => $otherTeam->id]);
        $invoice = Invoice::factory()->create([
            'team_id' => $otherTeam->id,
            'vendor_id' => $vendor->id,
            'file_path' => 'invoices/restricted.pdf',
        ]);

        Storage::disk('private')->put($invoice->file_path, 'pdf-content');

        $this->actingAs($user)
            ->get(route('vault.invoices.download', $invoice))
            ->assertForbidden();
    }

    /**
     * @return array{0: User, 1: Team}
     */
    private function createUserWithTeam(): array
    {
        $team = Team::factory()->create();
        $user = User::factory()->create([
            'current_team_id' => $team->id,
        ]);

        $user->teams()->attach($team);

        return [$user, $team];
    }
}
