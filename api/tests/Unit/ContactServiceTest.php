<?php

namespace Tests\Unit;

use App\Mail\ContactCreated;
use App\Models\Contact;
use App\Services\ContactService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ContactServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ContactService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ContactService();
    }

    /** @test */
    public function it_creates_a_contact_and_sends_email()
    {
        Mail::fake();

        $data = [
            'name' => 'João Teste',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
            'zip_code' => '01001-000',
            'state' => 'SP',
            'city' => 'São Paulo',
            'neighborhood' => 'Centro',
            'address' => 'Rua A',
            'number' => '123'
        ];

        $contact = $this->service->create($data);

        $this->assertInstanceOf(Contact::class, $contact);
        $this->assertDatabaseHas('contacts', ['email' => 'joao@example.com']);

        Mail::assertSent(ContactCreated::class, function ($mail) use ($contact) {
            return $mail->contact->email === $contact->email;
        });
    }

    /** @test */
    public function it_updates_a_contact()
    {
        $contact = Contact::factory()->create(['name' => 'Original']);

        $updated = $this->service->update($contact, ['name' => 'Atualizado']);

        $this->assertEquals('Atualizado', $updated->name);
        $this->assertDatabaseHas('contacts', ['id' => $contact->id, 'name' => 'Atualizado']);
    }

    /** @test */
    public function it_deletes_a_contact()
    {
        $contact = Contact::factory()->create();

        $this->service->delete($contact);

        $this->assertDatabaseMissing('contacts', ['id' => $contact->id]);
    }

    /** @test */
    public function it_lists_contacts_paginated()
    {
        Contact::factory()->count(15)->create();

        $paginated = $this->service->list();

        $this->assertEquals(10, $paginated->count()); // Laravel padrão de paginação = 10
    }
}
