<?php

Breadcrumbs::register('dashboard', function($breadcrumbs) {
    $breadcrumbs->push('Home', url('dashboard'));
});

Breadcrumbs::register('clientss.index', function($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Colegios', route('clientss.index'));
});

Breadcrumbs::register('clientss.classrooms.index', function($breadcrumbs, $client) {
  $breadcrumbs->parent('clientss.index');
  $breadcrumbs->push($client->name, route('clientss.index', $client->id));
});