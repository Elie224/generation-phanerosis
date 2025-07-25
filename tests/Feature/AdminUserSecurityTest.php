<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AdminUserSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_main_admin_can_modify_users()
    {
        // Créer l'administrateur principal
        $mainAdmin = User::factory()->create([
            'email' => 'kouroumaelisee@gmail.com',
            'role' => 'admin'
        ]);

        // Créer un administrateur normal
        $normalAdmin = User::factory()->create([
            'role' => 'admin'
        ]);

        // Créer un pasteur
        $pastor = User::factory()->create([
            'role' => 'pasteur'
        ]);

        // Créer un utilisateur à modifier
        $userToModify = User::factory()->create([
            'role' => 'member'
        ]);

        // Test 1: L'administrateur principal peut modifier un utilisateur
        $this->actingAs($mainAdmin)
            ->put("/admin/users/{$userToModify->id}", [
                'name' => 'Nouveau Nom',
                'email' => 'nouveau@email.com',
                'role' => 'leader'
            ])
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('success');

        // Vérifier que l'utilisateur a été modifié
        $this->assertDatabaseHas('users', [
            'id' => $userToModify->id,
            'name' => 'Nouveau Nom',
            'email' => 'nouveau@email.com',
            'role' => 'leader'
        ]);

        // Test 2: L'administrateur normal NE PEUT PAS modifier un utilisateur
        $this->actingAs($normalAdmin)
            ->put("/admin/users/{$userToModify->id}", [
                'name' => 'Tentative de modification',
                'email' => 'tentative@email.com',
                'role' => 'member'
            ])
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');

        // Vérifier que l'utilisateur n'a PAS été modifié
        $this->assertDatabaseHas('users', [
            'id' => $userToModify->id,
            'name' => 'Nouveau Nom', // Doit rester inchangé
            'email' => 'nouveau@email.com' // Doit rester inchangé
        ]);

        // Test 3: Le pasteur NE PEUT PAS modifier un utilisateur
        $this->actingAs($pastor)
            ->put("/admin/users/{$userToModify->id}", [
                'name' => 'Tentative par pasteur',
                'email' => 'pasteur@email.com',
                'role' => 'member'
            ])
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');

        // Vérifier que l'utilisateur n'a PAS été modifié
        $this->assertDatabaseHas('users', [
            'id' => $userToModify->id,
            'name' => 'Nouveau Nom', // Doit rester inchangé
            'email' => 'nouveau@email.com' // Doit rester inchangé
        ]);
    }

    public function test_only_main_admin_can_delete_users()
    {
        // Créer l'administrateur principal
        $mainAdmin = User::factory()->create([
            'email' => 'kouroumaelisee@gmail.com',
            'role' => 'admin'
        ]);

        // Créer un administrateur normal
        $normalAdmin = User::factory()->create([
            'role' => 'admin'
        ]);

        // Créer un utilisateur à supprimer
        $userToDelete = User::factory()->create([
            'role' => 'member'
        ]);

        // Test 1: L'administrateur principal peut supprimer un utilisateur
        $this->actingAs($mainAdmin)
            ->delete("/admin/users/{$userToDelete->id}")
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('success');

        // Vérifier que l'utilisateur a été supprimé
        $this->assertDatabaseMissing('users', [
            'id' => $userToDelete->id
        ]);

        // Créer un autre utilisateur à supprimer
        $userToDelete2 = User::factory()->create([
            'role' => 'member'
        ]);

        // Test 2: L'administrateur normal NE PEUT PAS supprimer un utilisateur
        $this->actingAs($normalAdmin)
            ->delete("/admin/users/{$userToDelete2->id}")
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');

        // Vérifier que l'utilisateur n'a PAS été supprimé
        $this->assertDatabaseHas('users', [
            'id' => $userToDelete2->id
        ]);
    }

    public function test_main_admin_cannot_be_modified()
    {
        // Créer l'administrateur principal
        $mainAdmin = User::factory()->create([
            'email' => 'kouroumaelisee@gmail.com',
            'role' => 'admin'
        ]);

        // Créer un autre administrateur principal (pour le test)
        $anotherMainAdmin = User::factory()->create([
            'email' => 'autre@email.com',
            'role' => 'admin'
        ]);

        // Test: Même l'administrateur principal ne peut pas se modifier lui-même
        $this->actingAs($mainAdmin)
            ->put("/admin/users/{$mainAdmin->id}", [
                'name' => 'Tentative de modification',
                'email' => 'kouroumaelisee@gmail.com',
                'role' => 'admin'
            ])
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');

        // Vérifier que l'administrateur principal n'a PAS été modifié
        $this->assertDatabaseHas('users', [
            'id' => $mainAdmin->id,
            'email' => 'kouroumaelisee@gmail.com',
            'role' => 'admin'
        ]);
    }

    public function test_main_admin_cannot_be_deleted()
    {
        // Créer l'administrateur principal
        $mainAdmin = User::factory()->create([
            'email' => 'kouroumaelisee@gmail.com',
            'role' => 'admin'
        ]);

        // Test: Même l'administrateur principal ne peut pas se supprimer
        $this->actingAs($mainAdmin)
            ->delete("/admin/users/{$mainAdmin->id}")
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');

        // Vérifier que l'administrateur principal n'a PAS été supprimé
        $this->assertDatabaseHas('users', [
            'id' => $mainAdmin->id,
            'email' => 'kouroumaelisee@gmail.com'
        ]);
    }

    public function test_user_cannot_modify_themselves()
    {
        // Créer l'administrateur principal
        $mainAdmin = User::factory()->create([
            'email' => 'kouroumaelisee@gmail.com',
            'role' => 'admin'
        ]);

        // Test: L'administrateur principal ne peut pas se modifier lui-même
        $this->actingAs($mainAdmin)
            ->put("/admin/users/{$mainAdmin->id}", [
                'name' => 'Tentative d\'auto-modification',
                'email' => 'kouroumaelisee@gmail.com',
                'role' => 'admin'
            ])
            ->assertRedirect(route('admin.users'))
            ->assertSessionHas('error');
    }
}
