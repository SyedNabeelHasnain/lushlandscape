<?php

use App\Services\BlockBuilderService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::command('sitemap:generate')->daily();

