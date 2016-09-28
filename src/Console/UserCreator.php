<?php
namespace Duan\Console;

use Duan\DuanApp;
use Duan\Models\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UserCreator extends Command
{
    protected function configure()
    {
        $this
            ->setName('create_user')
            ->setDescription("create a user from command line")
            ->addArgument(
                'env',
                InputArgument::REQUIRED,
                'The environment used, (local|staging|production)'
            )
            ->addArgument(
                'email',
                InputArgument::REQUIRED,
                'Email address for registration.'
            )
            ->addArgument(
                'password',
                InputArgument::REQUIRED,
                'Plaintext password, hash will be generated for DB storage.'
            )
            ->addArgument(
                'alias',
                InputArgument::REQUIRED,
                'Optional alias.'
            )
            ->addArgument(
                'first_name',
                InputArgument::OPTIONAL,
                'Optional first name.'
            )
            ->addArgument(
                'last_name',
                InputArgument::OPTIONAL,
                'Optional last name.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $duan = new DuanApp($input->getArgument('env'));
        $duan->configure();

        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $alias = $input->getArgument('alias');
        $firstName = $input->getArgument('first_name');
        $lastName = $input->getArgument('last_name');

        $user = new User;

        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_BCRYPT);

        if ($alias) {
            $user->alias = $alias;
        }
        if ($firstName) {
            $user->first_name = $firstName;
        }
        if ($lastName) {
            $user->last_name = $lastName;
        }

        try {
            $user->save();
            $output->writeln("User identified with $user->email created.");
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }

    }

}
