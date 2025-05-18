<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use App\Mail\ContactCreated;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_creates_a_contact_and_sends_email()
    {
        Mail::fake();

        $payload = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
            'zip_code' => '01001-000',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'address' => 'Rua A',
            'number' => '123'
        ];

        $response = $this->postJson('/api/contacts', $payload);

        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', ['email' => 'joao@example.com']);

        Mail::assertSent(ContactCreated::class, function ($mail) use ($payload) {
            return $mail->contact->email === $payload['email'];
        });
    }

    /** @test */
    public function it_updates_a_contact()
    {
        $contact = Contact::factory()->create([
            'name' => 'Original Name',
        ]);

        $response = $this->putJson("/api/contacts/{$contact->id}", [
            'name' => 'Nome Atualizado',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', ['name' => 'Nome Atualizado']);
    }

    /** @test */
    public function it_deletes_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->deleteJson("/api/contacts/{$contact->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function it_lists_contacts()
    {
        Contact::factory()->count(5)->create();

        $response = $this->getJson('/api/contacts');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'links'
        ]);
    }

    /** @test */
    public function it_shows_a_contact()
    {
        $contact = Contact::factory()->create();

        $response = $this->getJson("/api/contacts/{$contact->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'email' => $contact->email
        ]);
    }
}
