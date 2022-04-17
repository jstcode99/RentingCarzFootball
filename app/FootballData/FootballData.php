<?php
namespace App\FootballData;
use Illuminate\Support\Facades\Http;

class FootballData {


    // protected $token;
    // protected $domain;

    // After of chagne at macro to test direct
    // public function __construct()
    // {	
    //     $this->token = env('FootballData_API_KEY');
    //     $this->domain = env('FootballData_DOMAIN');
    // }

    ##COMPETITION/LEAGUE
	
	/**
	 * List all available competitions.
	 *
	 * @param String $filter : '2077, ...' String with Separet with comets
	 * @return Collection
	 */
	public function getLeagues(String $filter = '') {
        $response = Http::footballData()->get('v2/competitions', ['areas' => $filter]);
        return $response->json($key = null);
	}

	/**
	 * List one particular competition.
	 *
	 * @param integer $leagueID 
	 * @param String $filter : '2077, ...' String (comma separated array)
	 * @return Collection
	 */
	public function getLeague(int $leagueID, String $filter = '') {
        $response = Http::footballData()->get('v2/competitions/'.$leagueID.'/', ['areas' => $filter]);
        return $response->json($key = null);
	}

	/**
	 * List all teams for a particular competition.
	 *
	 * @param integer $leagueID
	 * @param String $filter
	 * @return Collection
	 */
	public function  getLeagueTeams(int $leagueID, String $filter = '') {
		$response = Http::footballData()->get('v2/competitions/'.$leagueID.'/', ['stage' => $filter]);
		return $response->json($key = null);
	}

	/**
	 * Show Standings for a particular competition
	 *
	 * @param integer $leagueID
	 * @return Collection
	 */
	public function getLeagueStandings(int $leagueID) {
        $response = Http::footballData()->get('v2/competitions/'.$leagueID.'/standings');
		return $response->json($key = null);
	}

	/**
	 * List all matches for a particular competition.
	 *
	 * @param String $leagueID
	 * @param String $filters ['dateFrom' => '', 'dateTo' => '', 'stage' => '', 'status' => '', 'matchday' => '', 'group' => '']
	 * @return Collection
	 */
	public function getLeagueMatches(String $leagueID, String $filters = '' ) {
        $objeFilters = json_decode($filters, true);
        $response = Http::footballData()->get('v2/competitions/'.$leagueID.'/matches', $objeFilters);
		return $response;
	}

}