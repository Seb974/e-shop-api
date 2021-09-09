<?php

namespace App\Command;

use App\Service\Email\BackupSender;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackupCommand extends Command
{
    protected $name;
    protected $user;
    protected $pass;
    protected $path;
    protected $backupSender;
    protected static $defaultName = 'app:backup';
    protected static $defaultDescription = 'Create and send to admin a database backup';

    public function __construct($name, $user, $pass, $path, BackupSender $backupSender)
    {
        Parent::__construct();
        $this->name = $name;
        $this->user = $user;
        $this->pass = $pass;
        $this->path = $path;
        $this->backupSender = $backupSender;
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $command = [
            '/usr/bin/mysqldump',
            '--add-drop-table',
            '--skip-comments',
            '--default-character-set=utf8mb4',
            '--user="${:USER}"',
            '--password="${:PASSWORD}"',
            '--result-file="${:OUTPUT_FILE}"',
            '"${:DATABASE}"',
        ];

        $parameters = [
            'USER'        => $this->user,
            'PASSWORD'    => $this->pass,
            'DATABASE'    => $this->name,
            'OUTPUT_FILE' => $this->path . 'backup.sql',
        ];

        $process = Process::fromShellCommandline(implode(' ', $command));
        $process->run(null, $parameters);

        $sendStatus = $this->backupSender->send($this->path . 'backup.sql');

        if ($process->isSuccessful() && $sendStatus === 'done') {
            $io->success('Database successfully saved and sent');
            return Command::SUCCESS;
        } else {
            if (!$process->isSuccessful())
                $io->error('The database couldn\'t be saved.');
            else
                $io->error('The database has been saved but could not been sent to admin.');

            return Command::FAILURE;
        }

    }
}
