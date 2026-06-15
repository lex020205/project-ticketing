<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        Role::firstOrCreate(['nama_role' => 'Admin'], ['deskripsi' => 'Admin']);
        Role::firstOrCreate(['nama_role' => 'SPV'], ['deskripsi' => 'SPV']);
        Role::firstOrCreate(['nama_role' => 'Teknisi'], ['deskripsi' => 'Teknisi']);
    }

    /**
     * Test redirect route is available.
     */
    public function test_google_redirect_route_works()
    {
        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('scopes')
            ->with(['openid', 'profile', 'email'])
            ->andReturnSelf();
        
        $provider->shouldReceive('redirect')
            ->andReturn(redirect('https://accounts.google.com/o/oauth2/auth'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->get(route('google.redirect'));

        $response->assertRedirect('https://accounts.google.com/o/oauth2/auth');
    }

    /**
     * Test registered and active user can login.
     */
    public function test_active_registered_user_can_login_via_google()
    {
        $role = Role::where('nama_role', 'Teknisi')->first();
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('google-id-123');
        $googleUser->shouldReceive('getEmail')->andReturn('john.doe@example.com');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/john');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('google.callback'));

        // Check if user is logged in
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());

        // Check user model updated
        $user->refresh();
        $this->assertEquals('google-id-123', $user->google_id);
        $this->assertEquals('https://avatar.url/john', $user->google_avatar);
        $this->assertNotNull($user->email_verified_at);

        // Redirect to Teknisi dashboard
        $response->assertRedirect(route('teknisi.dashboard'));
    }

    /**
     * Test non-active user is rejected.
     */
    public function test_inactive_registered_user_is_denied_login()
    {
        $role = Role::where('nama_role', 'Teknisi')->first();
        $user = User::create([
            'name' => 'John Doe Inactive',
            'email' => 'john.inactive@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'nonaktif',
        ]);

        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('google-id-456');
        $googleUser->shouldReceive('getEmail')->andReturn('john.inactive@example.com');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/john');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('google.callback'));

        // Check if user is NOT logged in
        $this->assertFalse(Auth::check());

        // Redirect back to login with error
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Akun Anda tidak aktif. Silakan hubungi SPV.');
    }

    /**
     * Test unregistered email is rejected.
     */
    public function test_unregistered_email_is_denied_login()
    {
        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('google-id-789');
        $googleUser->shouldReceive('getEmail')->andReturn('unregistered@example.com');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/unregistered');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('google.callback'));

        // Check if user is NOT logged in
        $this->assertFalse(Auth::check());

        // Redirect back to login with error
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'Akun Google Anda belum terdaftar. Hubungi SPV untuk membuat akun.');
    }

    /**
     * Test user role is not modified during google login.
     */
    public function test_role_does_not_change_on_google_login()
    {
        $role = Role::where('nama_role', 'SPV')->first();
        $user = User::create([
            'name' => 'Jane SPV',
            'email' => 'jane.spv@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('google-id-spv');
        $googleUser->shouldReceive('getEmail')->andReturn('jane.spv@example.com');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/jane');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('google.callback'));

        $this->assertTrue(Auth::check());
        $user->refresh();
        $this->assertEquals($role->id, $user->role_id);
        $response->assertRedirect(route('spv.dashboard'));
    }

    /**
     * Test linking based on email when google_id is not yet set.
     */
    public function test_google_id_links_to_existing_user_by_email()
    {
        $role = Role::where('nama_role', 'Admin')->first();
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin.user@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
            'google_id' => null,
        ]);

        $googleUser = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUser->shouldReceive('getId')->andReturn('google-id-admin');
        $googleUser->shouldReceive('getEmail')->andReturn('admin.user@example.com');
        $googleUser->shouldReceive('getAvatar')->andReturn('https://avatar.url/admin');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($googleUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('google.callback'));

        $this->assertTrue(Auth::check());
        $user->refresh();
        $this->assertEquals('google-id-admin', $user->google_id);
        $response->assertRedirect(route('admin.dashboard'));
    }

    /**
     * Test domain restriction settings for an allowed email.
     */
    public function test_google_login_domain_restriction_allowed()
    {
        // Set allowed domain to ac.id
        config(['services.google.allowed_domain' => 'ac.id']);
        putenv('GOOGLE_ALLOWED_DOMAIN=ac.id');
        $_ENV['GOOGLE_ALLOWED_DOMAIN'] = 'ac.id';
        $_SERVER['GOOGLE_ALLOWED_DOMAIN'] = 'ac.id';
        if (class_exists(\Illuminate\Support\Env::class)) {
            \Illuminate\Support\Env::getRepository()->set('GOOGLE_ALLOWED_DOMAIN', 'ac.id');
        }

        $role = Role::where('nama_role', 'Teknisi')->first();
        $user = User::create([
            'name' => 'Student User',
            'email' => 'student@ac.id',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        $googleUserOk = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUserOk->shouldReceive('getId')->andReturn('google-id-ok');
        $googleUserOk->shouldReceive('getEmail')->andReturn('student@ac.id');
        $googleUserOk->shouldReceive('getAvatar')->andReturn('https://avatar.url/student');

        $providerOk = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $providerOk->shouldReceive('user')->andReturn($googleUserOk);

        Socialite::shouldReceive('driver')->with('google')->andReturn($providerOk);

        $responseOk = $this->get(route('google.callback'));
        $this->assertTrue(Auth::check());

        // Clean up config and putenv
        config(['services.google.allowed_domain' => null]);
        putenv('GOOGLE_ALLOWED_DOMAIN=');
        $_ENV['GOOGLE_ALLOWED_DOMAIN'] = '';
        $_SERVER['GOOGLE_ALLOWED_DOMAIN'] = '';
        if (class_exists(\Illuminate\Support\Env::class)) {
            \Illuminate\Support\Env::getRepository()->set('GOOGLE_ALLOWED_DOMAIN', '');
        }
    }

    /**
     * Test domain restriction settings for a disallowed email.
     */
    public function test_google_login_domain_restriction_disallowed()
    {
        // Set allowed domain to ac.id
        config(['services.google.allowed_domain' => 'ac.id']);
        putenv('GOOGLE_ALLOWED_DOMAIN=ac.id');
        $_ENV['GOOGLE_ALLOWED_DOMAIN'] = 'ac.id';
        $_SERVER['GOOGLE_ALLOWED_DOMAIN'] = 'ac.id';
        if (class_exists(\Illuminate\Support\Env::class)) {
            \Illuminate\Support\Env::getRepository()->set('GOOGLE_ALLOWED_DOMAIN', 'ac.id');
        }

        $role = Role::where('nama_role', 'Teknisi')->first();
        $userBadDomain = User::create([
            'name' => 'Bad Domain User',
            'email' => 'student@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        $googleUserBad = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUserBad->shouldReceive('getId')->andReturn('google-id-bad');
        $googleUserBad->shouldReceive('getEmail')->andReturn('student@gmail.com');
        $googleUserBad->shouldReceive('getAvatar')->andReturn('https://avatar.url/student-bad');

        $providerBad = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $providerBad->shouldReceive('user')->andReturn($googleUserBad);

        Socialite::shouldReceive('driver')->with('google')->andReturn($providerBad);

        $responseBad = $this->get(route('google.callback'));
        $this->assertFalse(Auth::check());
        $responseBad->assertRedirect(route('login'));
        $responseBad->assertSessionHas('error', 'Email dengan domain ini tidak diizinkan untuk login.');

        // Clean up config and putenv
        config(['services.google.allowed_domain' => null]);
        putenv('GOOGLE_ALLOWED_DOMAIN=');
        $_ENV['GOOGLE_ALLOWED_DOMAIN'] = '';
        $_SERVER['GOOGLE_ALLOWED_DOMAIN'] = '';
        if (class_exists(\Illuminate\Support\Env::class)) {
            \Illuminate\Support\Env::getRepository()->set('GOOGLE_ALLOWED_DOMAIN', '');
        }
    }

    /**
     * Test domain restriction settings with multiple allowed domains.
     */
    public function test_google_login_multiple_domains_restriction()
    {
        // Set multiple allowed domains
        config(['services.google.allowed_domain' => 'uksw.edu, gmail.com']);
        putenv('GOOGLE_ALLOWED_DOMAIN=uksw.edu, gmail.com');
        $_ENV['GOOGLE_ALLOWED_DOMAIN'] = 'uksw.edu, gmail.com';
        $_SERVER['GOOGLE_ALLOWED_DOMAIN'] = 'uksw.edu, gmail.com';
        if (class_exists(\Illuminate\Support\Env::class)) {
            \Illuminate\Support\Env::getRepository()->set('GOOGLE_ALLOWED_DOMAIN', 'uksw.edu, gmail.com');
        }

        $role = Role::where('nama_role', 'Teknisi')->first();

        // 1. First allowed domain (uksw.edu)
        User::create([
            'name' => 'UKSW Student',
            'email' => 'student@uksw.edu',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        // 2. Second allowed domain (gmail.com)
        User::create([
            'name' => 'Gmail User',
            'email' => 'user@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        // 3. Disallowed domain (yahoo.com)
        User::create([
            'name' => 'Yahoo User',
            'email' => 'user@yahoo.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        $googleUserOk1 = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUserOk1->shouldReceive('getId')->andReturn('google-id-ok1');
        $googleUserOk1->shouldReceive('getEmail')->andReturn('student@uksw.edu');
        $googleUserOk1->shouldReceive('getAvatar')->andReturn('https://avatar.url/ok1');

        $googleUserOk2 = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUserOk2->shouldReceive('getId')->andReturn('google-id-ok2');
        $googleUserOk2->shouldReceive('getEmail')->andReturn('user@gmail.com');
        $googleUserOk2->shouldReceive('getAvatar')->andReturn('https://avatar.url/ok2');

        $googleUserBad = Mockery::mock('Laravel\Socialite\Two\User');
        $googleUserBad->shouldReceive('getId')->andReturn('google-id-bad');
        $googleUserBad->shouldReceive('getEmail')->andReturn('user@yahoo.com');
        $googleUserBad->shouldReceive('getAvatar')->andReturn('https://avatar.url/bad');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturnValues([$googleUserOk1, $googleUserOk2, $googleUserBad]);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        // Call 1: uksw.edu (Allowed)
        $response1 = $this->get(route('google.callback'));
        $this->assertTrue(Auth::check());
        Auth::logout();

        // Call 2: gmail.com (Allowed)
        $response2 = $this->get(route('google.callback'));
        $this->assertTrue(Auth::check());
        Auth::logout();

        // Call 3: yahoo.com (Disallowed)
        $response3 = $this->get(route('google.callback'));
        $this->assertFalse(Auth::check());
        $response3->assertRedirect(route('login'));
        $response3->assertSessionHas('error', 'Email dengan domain ini tidak diizinkan untuk login.');

        // Clean up config and putenv
        config(['services.google.allowed_domain' => null]);
        putenv('GOOGLE_ALLOWED_DOMAIN=');
        $_ENV['GOOGLE_ALLOWED_DOMAIN'] = '';
        $_SERVER['GOOGLE_ALLOWED_DOMAIN'] = '';
        if (class_exists(\Illuminate\Support\Env::class)) {
            \Illuminate\Support\Env::getRepository()->set('GOOGLE_ALLOWED_DOMAIN', '');
        }
    }

    /**
     * Test regular login still works.
     */
    public function test_regular_login_still_works()
    {
        $role = Role::where('nama_role', 'Teknisi')->first();
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'regular@example.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'nomor_telepon' => '0812345678',
            'status_user' => 'aktif',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'regular@example.com',
            'password' => 'password',
        ]);

        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
        $response->assertRedirect(route('teknisi.dashboard'));
    }
}
