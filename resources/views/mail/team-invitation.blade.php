<x-mail::message>
# Team Invitation

You have been invited to join the **{{ $invitation->team->name }}** team.

<x-mail::button :url="$acceptUrl">
Accept Invitation
</x-mail::button>

If you did not expect to receive this invitation, you may ignore this email.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
