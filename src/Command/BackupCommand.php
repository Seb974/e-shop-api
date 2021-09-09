<?php

namespace App\Command;

use phpDocumentor\Reflection\Types\Parent_;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class BackupCommand extends Command
{
    protected $name;
    protected $user;
    protected $pass;
    protected $path;
    protected static $defaultName = 'app:backup';
    protected static $defaultDescription = 'Create a database backup';

    public function __construct($name, $user, $pass, $path)
    {
        Parent::__construct();
        $this->name = $name;
        $this->user = $user;
        $this->pass = $pass;
        $this->path = $path;
    }

    protected function configure()
    {
        $this->setDescription(self::$defaultDescription);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $process = new Process(['mysqldump --user="${:db_user}" --password="${:db_pass}" "${:db_name}" > "${:db_backup_path}"']);
        $process->run(null, [
            'db_user' => $this->user,
            'db_password' => $this->pass,
            'db_name' => $this->name,
            'db_backup_path' => $this->path . 'db-'.time().'.sql',
        ]);

        $io->success('Database successfully saved');

        return Command::SUCCESS;
    }
}
