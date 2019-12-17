<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Index;

use Elasticsearch\Client as ElasticClient;
use Exception;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WTG\Models\Synonym;

/**
 * Index import command.
 *
 * @package     WTG\Console
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Recreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'index:recreate {index : The alias of an index}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recreate an elasticsearch index.';

    /**
     * @var ElasticClient
     */
    protected $elastic;

    /**
     * UpdateSettings constructor.
     *
     * @param ElasticClient $elastic
     */
    public function __construct(ElasticClient $elastic)
    {
        parent::__construct();

        $this->elastic = $elastic;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $indexAlias = $this->argument('index');
        $indexClient = $this->elastic->indices();

        try {
            $indices = array_keys($indexClient->get(['index' => $indexAlias]));
            $oldIndex = array_pop($indices);
        } catch (Exception $e) {
            $oldIndex = false;
        }

        $newIndex = $indexAlias . '-' . time();

        $config = config('scout.elasticsearch.config');
        $config['settings']['number_of_shards'] = config('scout.elasticsearch.number_of_shards');
        $config['settings']['number_of_replicas'] = config('scout.elasticsearch.number_of_replicas');

        $config['settings']['analysis']['filter']['synonym']['synonyms'] = Synonym::createMapping();

        try {
            $indexClient->create(
                [
                    'index' => $newIndex,
                    'body'  => $config,
                ]
            );

            if ($oldIndex) {
                $indexClient->delete(
                    [
                        'index' => $oldIndex,
                    ]
                );
            }

            $indexClient->putAlias(
                [
                    'index' => $newIndex,
                    'name'  => $indexAlias,
                ]
            );
        } catch (Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");

            return 1;
        }

        $output->writeln("<info>Index '$indexAlias' has been recreated</info>");

        return 0;
    }
}
