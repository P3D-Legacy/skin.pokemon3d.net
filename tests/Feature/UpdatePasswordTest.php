<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Http\Livewire\UpdatePasswordForm;
use Livewire\Livewire;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated()
    {
        $this->actingAs($user = User::factory()->create());

        $password = 'SuperSecret123!';
        $new_password = 'NotSoSuperSecret123!';
        Livewire::test(UpdatePasswordForm::class)
                ->set('state', [
                    'current_password' => $password,
                    'password' => $new_password,
                    'password_confirmation' => $new_password,
                ])
                ->call('updatePassword');

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_current_password_must_be_correct()
    {
        $this->actingAs($user = User::factory()->create());

        $new_password = 'SuperSecret123!2';
        Livewire::test(UpdatePasswordForm::class)
                ->set('state', [
                    'current_password' => 'wrong-password',
                    'password' => $new_password,
                    'password_confirmation' => $new_password,
                ])
                ->call('updatePassword')
                ->assertHasErrors(['current_password']);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_new_passwords_must_match()
    {
        $this->actingAs($user = User::factory()->create());

        $password = 'SuperSecret123!';
        $new_password = 'SuperSecret123!2';
        Livewire::test(UpdatePasswordForm::class)
                ->set('state', [
                    'current_password' => $password,
                    'password' => $new_password,
                    'password_confirmation' => 'wrong-password',
                ])
                ->call('updatePassword')
                ->assertHasErrors(['password']);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}
