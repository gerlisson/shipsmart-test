<h1>Novo contato cadastrado</h1>

<ul>
    <li><strong>Nome:</strong> {{ $contact->name }}</li>
    <li><strong>Email:</strong> {{ $contact->email }}</li>
    <li><strong>Telefone:</strong> {{ $contact->phone }}</li>
    <li><strong>Endereço:</strong> {{ $contact->address }}, {{ $contact->number }}</li>
    <li><strong>CEP:</strong> {{ $contact->zip_code }}</li>
</ul>
