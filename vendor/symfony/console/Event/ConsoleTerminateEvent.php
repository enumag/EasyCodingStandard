<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace ECSPrefix20210508\Symfony\Component\Console\Event;

use ECSPrefix20210508\Symfony\Component\Console\Command\Command;
use ECSPrefix20210508\Symfony\Component\Console\Input\InputInterface;
use ECSPrefix20210508\Symfony\Component\Console\Output\OutputInterface;
/**
 * Allows to manipulate the exit code of a command after its execution.
 *
 * @author Francesco Levorato <git@flevour.net>
 */
final class ConsoleTerminateEvent extends \ECSPrefix20210508\Symfony\Component\Console\Event\ConsoleEvent
{
    private $exitCode;
    /**
     * @param int $exitCode
     */
    public function __construct(\ECSPrefix20210508\Symfony\Component\Console\Command\Command $command, \ECSPrefix20210508\Symfony\Component\Console\Input\InputInterface $input, \ECSPrefix20210508\Symfony\Component\Console\Output\OutputInterface $output, $exitCode)
    {
        $exitCode = (int) $exitCode;
        parent::__construct($command, $input, $output);
        $this->setExitCode($exitCode);
    }
    /**
     * @return void
     * @param int $exitCode
     */
    public function setExitCode($exitCode)
    {
        $exitCode = (int) $exitCode;
        $this->exitCode = $exitCode;
    }
    /**
     * @return int
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }
}