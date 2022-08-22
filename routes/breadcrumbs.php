<?php

declare(strict_types=1);

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('tac-home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('homepage'));
});

Breadcrumbs::for('tac-search', function (BreadcrumbTrail $trail, $query) {
    $trail->parent('tac-home');
    $trail->push('Search Results for: '.$query, route('search.index', ['query' => $query]));
});

Breadcrumbs::for('conferences', function (BreadcrumbTrail $trail) {
    $trail->parent('tac-home');
    $trail->push('Conferences', route('conferences.index'));
});

Breadcrumbs::for('conference', function (BreadcrumbTrail $trail, $conference) {
    $trail->parent('conferences');
    $trail->push(
        $conference->name,
        $conference->canonical_url
    );
});

Breadcrumbs::for('events', function (BreadcrumbTrail $trail) {
    $trail->parent('tac-home');
    $trail->push('Events', route('events.index'));
});

Breadcrumbs::for('event', function (BreadcrumbTrail $trail, $event) {
    $trail->parent('conference', $event->conference);
    $trail->push(
        $event->name,
        $event->canonical_url,
    );
});

Breadcrumbs::for('speakers', function (BreadcrumbTrail $trail) {
    $trail->parent('tac-home');
    $trail->push('Speakers', route('speakers.index'));
});

Breadcrumbs::for('speaker', function (BreadcrumbTrail $trail, $speaker) {
    $trail->parent('speakers');
    $trail->push($speaker->name, route('speakers.show', ['speaker'=>$speaker->slug]));
});

Breadcrumbs::for('talks', function (BreadcrumbTrail $trail) {
    $trail->parent('tac-home');
    $trail->push('Talks', route('talks.index'));
});

Breadcrumbs::for('talk', function (BreadcrumbTrail $trail, $talk) {
    $trail->parent('event', $talk->event);
    $trail->push(
        $talk->title,
        $talk->canonical_url
    );
});
