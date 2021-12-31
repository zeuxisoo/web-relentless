<?php

namespace App\Console\Commands;

use App\Models\FoodMenu;
use Illuminate\Console\Command;
use MeiliSearch\Client;

class MeiliSearchConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meilisearch:config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the default config in MeiliSearch engine';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client(config("scout.meilisearch.host"), config("scout.meilisearch.key"));

        // FoodMenu
        $foodMenu = new FoodMenu();

        $indexes = $client->index($foodMenu->searchableAs());

        $indexes->updateSettings([
            "distinctAttribute" => "id",
            "filterableAttributes" => array_merge(
                ['id', 'tags', 'foods'],
                $foodMenu->getFillable()
            ),
            "rankingRules" => [
                "words",
                "typo",
                "proximity",
                "attribute",
                "sort",
                "exactness",
                "start_at:desc",
            ],
            "sortableAttributes" => [
                "start_at",
            ]
        ]);
    }
}
