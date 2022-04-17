<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompetitionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// List all available competitions.
Route::get('/get-leagues/{areas?}', function ($areas = '') {
    return FootballData::getLeagues($areas);
});

//List one particular competition.
Route::get('/get-league/{league}/{areas?}', function ($league, $areas = '') {
    return FootballData::getLeague($league, $areas);
});

//List all teams for a particular competition.
Route::get('/get-league-teams/{league}/{stage?}', function ($league, $stage = '') {
    return FootballData::getLeagueTeams($league, $stage);
});

// Show Standings for a particular competition
Route::get('/get-league-standings/{league}', function ($league) {
    return FootballData::getLeagueStandings($league);
});

// List all matches for a particular competition.
// array $filters = ['dateFrom' => '', 'dateTo' => '', 'stage' => '', 'status' => '', 'matchday' => '', 'group' => '']
// Route::get('/get-league-matches/{league}/filters/{filters?}', function ($league, $filters = '') {
//     return FootballData::getLeagueMatches($league, $filters);
// });

Route::get('/get-league-matches/{league}/filters/{filters?}', [CompetitionsController::class, 'getLeagueMatches']);

