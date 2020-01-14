<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Index;

use Elasticsearch\Client as ElasticClient;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Show all indices.
 *
 * @package     WTG\Console\Commands\Index
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Show extends Command
{
    /**
     * @var string
     */
    protected $name = 'index:show';

    /**
     * @var string
     */
    protected $description = 'Show all elastic indices.';

    /**
     * @var ElasticClient
     */
    protected ElasticClient $elastic;

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
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cat = $this->elastic->cat();
        $indices = $cat->indices();

        $this->getOutput()->table(array_keys(reset($indices)), array_values($indices));

        return 0;
    }
}
