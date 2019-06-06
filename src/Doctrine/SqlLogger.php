<?php
namespace Idealogica\OrmHelper\Doctrine;

use Doctrine\DBAL\Logging\SQLLogger as SqlLoggerInterface;
use Psr\Log\LoggerInterface;
use function Idealogica\OrmHelper\mixedToString;

/**
 * Class SqlLogger
 * @package Idealogica\OrmHelper\Doctrine
 */
class SqlLogger implements SqlLoggerInterface
{
    /**
     * @var null|LoggerInterface
     */
    protected $logger = null;

    /**
     * @var float|null
     */
    protected $start = null;

    /**
     * @var array
     */
    protected $currentQuery = '';

    /**
     * @var integer
     */
    protected $currentQueryIndex = 0;

    /**
     * DoctrineSqlLogger constructor.
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $sql
     * @param array|null $params
     * @param array|null $types
     */
    public function startQuery($sql, array $params = null, array $types = null)
    {
        $this->start = microtime(true);
        $this->currentQueryIndex++;
        if ($params) {
            foreach ($params as &$param) {
                $param = mixedToString($param);
            }
        }
        $this->currentQuery =
            $sql . PHP_EOL .
            ($params ? "[parameters: " . implode(', ', $params) . "]" . PHP_EOL : "") .
            ($types ? "[types: " . implode(', ', $types) . "]" . PHP_EOL : "");
    }

    /**
     *
     */
    public function stopQuery()
    {
        $this->currentQuery .= "[exec time: " . (microtime(true) - $this->start) . "]";
        $this->logger->info(print_r($this->currentQuery, true));
    }
}
