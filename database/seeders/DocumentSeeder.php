<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Document;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $documents = [
            [
                'content' => 'La création de ce rapport Power BI répond à un objectif clair : fournir un outil interactif et visuel pour analyser et suivre les performances commerciales et opérationnelles. Le rapport est conçu pour centraliser les données, faciliter leur exploration, et générer des insights utiles à la prise de décision stratégique. En utilisant des métriques clés telles que le chiffre d’affaires, le coût total des ventes, les tendances de ventes et les répartitions géographiques, ce rapport offre une vue d’ensemble complète et intuitive des activités.',
                'hash' => hash('sha256', 'Les algorithmes de machine learning nécessitent des jeux de données conséquents...')
            ],
            [
                'content' => 'Laravel utilise le pattern MVC pour structurer les applications web modernes...',
                'hash' => hash('sha256', 'Laravel utilise le pattern MVC pour structurer les applications web modernes...')
            ]
        ];
        foreach ($documents as $doc) {
            Document::create($doc);
        }
    }
}
