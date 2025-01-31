<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FootballService
{
    protected $apiUrl = 'https://api-football-v1.p.rapidapi.com/v3/';
    protected $rapidApiHost = 'api-football-v1.p.rapidapi.com';
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('API_FOOTBALL_KEY');
    }

    private function request($endpoint, $params = [])
    {
        try {
            $api = Http::withHeaders([
                'x-rapidapi-key' => $this->apiKey,
                'x-rapidapi-host' => $this->rapidApiHost
            ]);

            $response = $api->get($this->apiUrl . $endpoint, $params);
            $result = json_decode($response->body());

            return $result->response;
        }
        catch (\Exception $e) {
            Log::error('Erro na requisição da API', [
                'message' => $e->getMessage(),
                'endpoint' => $this->apiUrl . $endpoint,
                'params' => $params,
            ]);

            return [
                'error' => 'Falha ao buscar dados da API.',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Listar todas as ligas
     * @return array|mixed
     */
    public function getLeagues()
    {
        return $this->request('leagues');
    }

    /**
     * Listar todas as temporadas
     * @return array|mixed
     */
    public function getSeasons()
    {
        return $this->request('leagues/seasons');
    }

    /**
     * Listar países
     * @return array|mixed
     */
    public function getCountries()
    {
        $key = 'countries';

        if (!Cache::has('countries')) {
            Cache::remember($key, Carbon::now()->addDay(), function (){
                return $this->request('countries');
            });
        }

        $countries = Cache::get('countries');
        return $countries;
    }

    /**
     * Listar partidas
     * @param $leagueId // Id da Liga
     * @param $season // Temporada
     * @param $round // Rodada
     * @return array|mixed
     */
    public function getFixtures($leagueId, $season, $round)
    {
        return $this->request("fixtures", [
            'league' => $leagueId,
            'season' => $season,
            'round' => $round
        ]);
    }

    /** Eventos da partida
     * @param $fixtureId
     * @param $playerId
     * @param $teamId
     * @return array|mixed
     */
    public function getFixtureEvents($fixtureId, $playerId = '', $teamId = '')
    {
        return $this->request("fixtures/events", [
            'fixture' => $fixtureId,
            'player' => $playerId,
            'team' => $teamId
        ]);
    }
}
