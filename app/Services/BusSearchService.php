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

        return $query->get();
    }
}