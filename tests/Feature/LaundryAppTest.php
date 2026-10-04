<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Outlet;
use App\Models\Paket;
use App\Models\Member;
use App\Models\Transaksi;
use Database\Seeders\DatabaseSeeder;

class LaundryAppTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_landing_page_is_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Portal Petugas');
        $response->assertSee('Daftar Pelanggan');
        $response->assertSee('Lacak Cucian');
    }

    public function test_tracking_page_is_accessible_and_finds_invoice(): void
    {
        $response = $this->get('/tracking');
        $response->assertStatus(200);
        $response->assertSee('Lacak Cucian Anda');

        $trx = Transaksi::first();
        if ($trx) {
            $searchRes = $this->get('/tracking?invoice=' . $trx->kode_invoice);
            $searchRes->assertStatus(200);
            $searchRes->assertSee($trx->kode_invoice);
        }
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Laundry');
    }

    public function test_register_page_is_accessible(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSee('Registrasi Pelanggan');
    }

    public function test_customer_can_register_new_member(): void
    {
        $response = $this->post('/register', [
            'nama' => 'Pelanggan Setia Baru',
            'tlp' => '089988776655',
            'jenis_kelamin' => 'L',
            'alamat' => 'Jl. Sudirman No. 123',
        ]);

        $response->assertRedirect('/login');
        $this->assertDatabaseHas('tb_member', [
            'nama' => 'Pelanggan Setia Baru',
            'tlp' => '089988776655',
        ]);
    }

    public function test_admin_can_login(): void
    {
        $response = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_kasir_can_login(): void
    {
        $response = $this->post('/login', [
            'username' => 'kasir',
            'password' => 'kasir123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_owner_can_login(): void
    {
        $response = $this->post('/login', [
            'username' => 'owner',
            'password' => 'owner123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_admin_can_access_dashboard_and_masters(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard Ringkasan');

        $outletRes = $this->actingAs($admin)->get('/outlet');
        $outletRes->assertStatus(200);

        $paketRes = $this->actingAs($admin)->get('/paket');
        $paketRes->assertStatus(200);

        $userRes = $this->actingAs($admin)->get('/user');
        $userRes->assertStatus(200);

        $memberRes = $this->actingAs($admin)->get('/member');
        $memberRes->assertStatus(200);

        $trxRes = $this->actingAs($admin)->get('/transaksi');
        $trxRes->assertStatus(200);

        $laporanRes = $this->actingAs($admin)->get('/laporan');
        $laporanRes->assertStatus(200);
    }

    public function test_kasir_can_access_pos_and_receipt(): void
    {
        $kasir = User::where('username', 'kasir')->first();

        $response = $this->actingAs($kasir)->get('/transaksi/create');
        $response->assertStatus(200);
        $response->assertSee('Entri Transaksi Kasir');

        $trx = Transaksi::first();
        if ($trx) {
            $strukRes = $this->actingAs($kasir)->get("/transaksi/{$trx->id}/cetak-struk");
            $strukRes->assertStatus(200);

            $invRes = $this->actingAs($kasir)->get("/transaksi/{$trx->id}/cetak-invoice");
            $invRes->assertStatus(200);
        }
    }

    public function test_owner_can_access_laporan_and_print(): void
    {
        $owner = User::where('username', 'owner')->first();

        $laporanRes = $this->actingAs($owner)->get('/laporan');
        $laporanRes->assertStatus(200);

        $cetakRes = $this->actingAs($owner)->get('/laporan/cetak');
        $cetakRes->assertStatus(200);
        $cetakRes->assertSee('REKAP LAPORAN TRANSAKSI');
    }

    public function test_cannot_delete_self_or_user_with_transactions(): void
    {
        $admin = User::where('username', 'admin')->first();
        $kasir = User::where('username', 'kasir')->first();

        // 1. Admin cannot delete himself
        $delSelf = $this->actingAs($admin)->delete("/user/{$admin->id}");
        $delSelf->assertSessionHasErrors('error');

        // 2. Cannot delete kasir who has transactions
        $delKasir = $this->actingAs($admin)->delete("/user/{$kasir->id}");
        $delKasir->assertSessionHasErrors('error');

        // 3. Can delete fresh user without transactions
        $freshUser = User::create([
            'nama' => 'Pegawai Baru Magang',
            'username' => 'magang',
            'password' => 'magang123',
            'role' => 'kasir',
            'id_outlet' => $admin->id_outlet,
        ]);

        $delFresh = $this->actingAs($admin)->delete("/user/{$freshUser->id}");
        $delFresh->assertRedirect('/user');
        $this->assertDatabaseMissing('tb_user', ['id' => $freshUser->id]);
    }
}
