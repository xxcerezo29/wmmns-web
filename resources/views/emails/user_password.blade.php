<x-mail::message>
    # Hello, {{ $user->firstname }} {{ $user->lastname }}

    Your account has been successfully created! Below are your login credentials:

    #Email: {{ $user->email }}
    #Password: {{ $password }}

    Thanks,
    {{ config('app.name') }}
</x-mail::message>
