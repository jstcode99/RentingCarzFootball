<?php

namespace App\Http\Controllers;

use App\Models\Areas;
use App\Models\Competitions;
use App\Models\Seasons;
use App\Models\Matches;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\FootballData\Facades\FootballDataFacade as FootballData;

class CompetitionsController extends Controller
{

    
    /**
     * Display a listing match by competition and call
     * facade Footballdata to get resource and save data in database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function getLeagueMatches($league, $filters = '') {
        $response = FootballData::getLeagueMatches($league, $filters);
        $objeFilters = json_decode($filters, true);

        if ($response->successful()) {
            if(isset($response['errorCode'])) {
                // If error by membership!
                return response($response, $response['errorCode']);
            } else {
                if(isset($response['competition']['area'])) {

                    $responseCUArea = $this->updateOrCreatetArea(array(
                        'api_id' => $response['competition']['area']['id'],
                        'name' => $response['competition']['area']['name'],
                    ));

                    $responseCUCompetition = $this->updateOrCreatetCompetitions(array(
                        'api_id' => $response['competition']['id'],
                        'area_id' => $responseCUArea->id,
                        'name' => $response['competition']['name'],
                        'code' => $response['competition']['code'],
                    ));

                    $countMatchesApi = collect($response['matches'])->count();
                    $countMatchesDB = Matches::where('competition_id', $responseCUCompetition->id)
                        ->where('utc_date', '>=', $objeFilters['dateFrom'])
                        ->where('utc_date', '<=', $objeFilters['dateTo'])
                        ->count();

                    // check if the api has more new resources than the database
                    if($countMatchesApi > $countMatchesDB) {
                        // If true store all or update records
                        $this->upsertsSeasonsMatches($response['matches'], $responseCUCompetition->id);
                    }
                    // Aqui podria hacer un sql que haga left joins para traer la data propia 
                    // pero como en caso de nuevos registro o no la informacion es la misma por eso retorno matches

                    return response($response, 200);
                }
            }
        }
        if ($response->failed()) {
            return response($response, $response['errorCode']);
        }

    }

    /**
     * updateOrCreatet of Area.
     *
     * @return App\Models\Areas
     */
    public function updateOrCreatetArea(array $area = array()) {
        // updateOrCreate
        $response = Areas::updateOrCreate($area);
        return $response;
    }

    /**
     * updateOrCreatet of competition.
     *
     * @return App\Models\Competitions
     */
    public function updateOrCreatetCompetitions(array $competition = array()) {
        // updateOrCreate
        $response = Competitions::updateOrCreate($competition);
        return $response;
    }


    public function upsertsSeasonsMatches (array $matches = array(), int $competition_id) {
        $firstMatch = collect($matches)->first();
        $season = array(
            'api_id' => $firstMatch['season']['id'],
            'start_date' => $firstMatch['season']['startDate'],
            'end_date' => $firstMatch['season']['endDate'],
        );
        $responseCUSeason = Seasons::updateOrCreate($season);

        if(isset($responseCUSeason->api_id)) {
            $matchesNews = collect($matches)
            ->reject(function ($item) {
                // Valid if no have teams to match
                // (Esta validacion adicional debido a que la api esta regerando registros sin de partidos sin equipos)
                /**
                 *  id: 391831 match with empty teams
                 */
                return empty($item['homeTeam']['id']) || empty($item['awayTeam']['id']);
            })->map(function ($item) use ($competition_id, $responseCUSeason) {
                if(isset($item['season'])) {
                    return array(
                        'api_id' => $item['id'],
                        'status' => $item['status'],
                        'stage' => $item['stage'],
                        'group' => $item['group'],
                        'competition_id' =>$competition_id,
                        'season_id' => $responseCUSeason->api_id,
                        'home_team_id' => $item['homeTeam']['id'],
                        'away_team_id' => $item['awayTeam']['id'],
                        'utc_date' => date("Y-m-d H:i",strtotime($item['utcDate'])),
                    );
                }
            })->unique('api_id');

            $responseUPMatches = Matches::upsert($matchesNews->toArray(), ['api_id'], [
                'status',
                'stage',
                'group',
                'utc_date',
            ]);

            // Por tiempo no no pude el tema de los scores pero bueno es algo sencillo
        }
    }

}
