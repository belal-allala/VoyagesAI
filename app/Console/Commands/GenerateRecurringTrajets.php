<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RecurringTrajetService;

class GenerateRecurringTrajets extends Command
{
    protected $signature = 'trajets:generate-recurring {days=30 : Nombre de jours à générer à l\'avance}';
    protected $description = 'Génère les trajets récurrents pour les jours à venir';

    public function handle(RecurringTrajetService $service)
    {
        $days = (int)$this->argument('days');
        $generated = $service->generateUpcomingTrajets($days);
        
        $this->info(sprintf(
            'Génération terminée. %d trajets récurrents créés pour les %d prochains jours.',
            count($generated),
            $days
        ));
    }
}