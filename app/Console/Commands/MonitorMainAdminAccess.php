<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class MonitorMainAdminAccess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:monitor-access {--days=7 : Nombre de jours à analyser}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Surveiller les accès et tentatives d\'accès à l\'administrateur principal';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $logFile = storage_path('logs/laravel.log');
        
        if (!File::exists($logFile)) {
            $this->error('Fichier de log non trouvé.');
            return 1;
        }

        $this->info("Analyse des accès à l'administrateur principal sur les {$days} derniers jours...");
        $this->newLine();

        // Lire le fichier de log
        $logContent = File::get($logFile);
        $lines = explode("\n", $logContent);
        
        $mainAdminAccesses = [];
        $mainAdminActions = [];
        $attemptedModifications = [];
        $attemptedDeletions = [];

        foreach ($lines as $line) {
            if (strpos($line, 'Accès à l\'administrateur principal détecté') !== false) {
                $mainAdminAccesses[] = $line;
            } elseif (strpos($line, 'Action de l\'administrateur principal') !== false) {
                $mainAdminActions[] = $line;
            } elseif (strpos($line, 'Tentative de modification') !== false) {
                $attemptedModifications[] = $line;
            } elseif (strpos($line, 'Tentative de suppression') !== false) {
                $attemptedDeletions[] = $line;
            }
        }

        // Afficher les statistiques
        $this->info("📊 Statistiques de sécurité :");
        $this->table(
            ['Type d\'événement', 'Nombre'],
            [
                ['Accès à l\'admin principal', count($mainAdminAccesses)],
                ['Actions de l\'admin principal', count($mainAdminActions)],
                ['Tentatives de modification', count($attemptedModifications)],
                ['Tentatives de suppression', count($attemptedDeletions)],
            ]
        );

        // Afficher les tentatives suspectes
        if (!empty($attemptedModifications) || !empty($attemptedDeletions)) {
            $this->warn("⚠️  Tentatives suspectes détectées :");
            $this->newLine();
            
            foreach ($attemptedModifications as $attempt) {
                $this->line("🔴 Modification : " . $this->extractInfo($attempt));
            }
            
            foreach ($attemptedDeletions as $attempt) {
                $this->line("🔴 Suppression : " . $this->extractInfo($attempt));
            }
        } else {
            $this->info("✅ Aucune tentative suspecte détectée.");
        }

        // Afficher les actions récentes de l'admin principal
        if (!empty($mainAdminActions)) {
            $this->info("👤 Actions récentes de l'administrateur principal :");
            $this->newLine();
            
            $recentActions = array_slice($mainAdminActions, -5);
            foreach ($recentActions as $action) {
                $this->line("📝 " . $this->extractInfo($action));
            }
        }

        return 0;
    }

    private function extractInfo($logLine)
    {
        // Extraire les informations importantes de la ligne de log
        if (preg_match('/\[(.*?)\]/', $logLine, $matches)) {
            return $matches[1] ?? 'Information non disponible';
        }
        return 'Information non disponible';
    }
}
