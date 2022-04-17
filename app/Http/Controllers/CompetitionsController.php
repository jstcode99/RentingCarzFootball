<?php

namespace App\Http\Controllers;

use App\Models\Competitions;
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
        // Determine if the status code is >= 200 and < 300...
        if ($response->successful()) {
            if(isset($response['errorCode'])) {
                // If error by membership!
                return response($response, $response['errorCode']);
            } else {
                // $competitions = collect($response->matches))->map(function ($item) {
                //     return array(
                //         'api_id' => $item->id,
                //         'competition_id' => $response->competition->id,
                //         'name' => $item->id,
                //         'code' => ,
                //     );
                // })
                // Competitions::upsert([
                //     ['departure' => 'Oakland', 'destination' => 'San Diego', 'price' => 99],
                //     ['departure' => 'Chicago', 'destination' => 'New York', 'price' => 150]
                // ], ['departure', 'destination'], ['price']);
                return $response->json($key = null);
            }
        }
        // Determine if the status code is >= 400...
        if ($response->failed()) {
            return response($response, $response['errorCode']);
        }

    }

    public function mapAreas ($areas = array()) {
        // $competitions = collect($response->matches))->map(function ($item) {
        //     return array(
        //         'api_id' => $item->id,
        //         'competition_id' => $response->competition->id,
        //         'name' => $item->id,
        //         'code' => ,
        //     );
        // })
    }
}
