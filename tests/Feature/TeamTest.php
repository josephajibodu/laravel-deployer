<?php

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a team', function () {
    $user = User::factory()->create();

    $team = Team::create([
        'user_id' => $user->id,
        'name' => 'Test Team',
        'personal_team' => false,
    ]);

    expect($team)->toBeInstanceOf(Team::class);
    expect($team->name)->toBe('Test Team');
    expect($team->owner->id)->toBe($user->id);
});

it('can add a user to a team', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();

    $team = Team::create([
        'user_id' => $owner->id,
        'name' => 'Test Team',
        'personal_team' => false,
    ]);

    $team->users()->attach($member, ['role' => 'member']);

    expect($team->hasUser($member))->toBeTrue();
    expect($team->users->contains($member))->toBeTrue();
});

it('can check if user belongs to team', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();

    $team = Team::create([
        'user_id' => $owner->id,
        'name' => 'Test Team',
        'personal_team' => false,
    ]);

    $team->users()->attach($member, ['role' => 'member']);

    expect($owner->belongsToTeam($team))->toBeTrue();
    expect($member->belongsToTeam($team))->toBeTrue();
});

it('can get user team role', function () {
    $owner = User::factory()->create();
    $member = User::factory()->create();

    $team = Team::create([
        'user_id' => $owner->id,
        'name' => 'Test Team',
        'personal_team' => false,
    ]);

    $team->users()->attach($member, ['role' => 'member']);

    expect($owner->teamRole($team))->toBe('owner');
    expect($member->teamRole($team))->toBe('member');
});

it('can create team invitation', function () {
    $owner = User::factory()->create();

    $team = Team::create([
        'user_id' => $owner->id,
        'name' => 'Test Team',
        'personal_team' => false,
    ]);

    $invitation = $team->teamInvitations()->create([
        'email' => 'test@example.com',
        'role' => 'member',
    ]);

    expect($invitation)->toBeInstanceOf(\App\Models\TeamInvitation::class);
    expect($invitation->email)->toBe('test@example.com');
    expect($invitation->team->id)->toBe($team->id);
});
