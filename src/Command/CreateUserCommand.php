<?php
namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

#[AsCommand(
    name: 'create-user',
    description: 'Create a new user',
)]
class CreateUserCommand extends Command
{
    private UserPasswordHasherInterface $passwordHasher;  // Updated property
    private EntityManagerInterface $em;

    // Inject PasswordHasherInterface instead of the old encoder
    public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        parent::__construct();
        $this->passwordHasher = $passwordHasher;  // Updated assignment
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Create a new User entity
        $user = new User();
        $user->setEmail('user@example.com');
        
        // Hash password using PasswordHasherInterface
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'password123');  // Updated
        $user->setPassword($hashedPassword);

        // Persist the user to the database
        $this->em->persist($user);
        $this->em->flush();

        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // Handle the option if needed
        }

        // Print success message
        $io->success('User has been created with email: ' . $user->getEmail());

        return Command::SUCCESS;
    }
}
