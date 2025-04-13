<?php

namespace App\Services;

use App\Models\Bus;

class BusSearchService
{
    public function searchBuses(array $filters)
    {
        $query = Bus::query();

        if (isset($filters['destination_city'])) {
            $query->where('destination_city', $filters['destination_city']);
        }

        if (isset($filters['departure_city'])) {
            $query->where('departure_city', $filters['departure_city']);
        }

        if (isset($filters['departure_time'])) {
            $query->where('departure_time', $filters['departure_time']);
        }

        return $query->get();
    }
}