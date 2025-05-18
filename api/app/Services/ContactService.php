<?php

namespace App\Services;

use App\Mail\ContactCreated;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    public function list(): LengthAwarePaginator
    {
        return Contact::paginate(10);
    }

    public function create(array $data): Contact
    {
        $contact = Contact::create($data);
        Mail::to(env('MAIL_DESTINATARIO_CONTATO'))->send(new ContactCreated($contact));
        return $contact;
    }

    public function update(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        return $contact;
    }

    public function delete(Contact $contact): void
    {
        $contact->delete();
    }
}
