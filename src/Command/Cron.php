<?php

namespace App\Command;

use App\Entity\Decision;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
    Command --save => don't save to the database
    Command --nooutput => don't save to the database
*/

#[AsCommand(name: 'app:database')]
class Cron extends Command
{
    public const OPTIONS = [
        'save' => 'save',
        'output' => 'nooutput'
    ];

    public function outputMessage(InputInterface $input, OutputInterface $output, string $message): void
    {
        if (!$input->getOption(self::OPTIONS['output'])) {
            $output->writeln($message);
        }
    }

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command allows you to update the database history');
        $this->addOption(
            self::OPTIONS['save']
        );
        $this->addOption(
            self::OPTIONS['output']
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entityManager = $this->entityManager;
        $decisionRepository = $entityManager->getRepository(Decision::class);

        $decisions = $decisionRepository->findByStatusEndAt();

        foreach ($decisions as $decision) {
            $decision->setStatus(Decision::STATUS_FIRST_DECISION);
            if ($input->getOption(self::OPTIONS['save'])) {
                $decisionRepository->save($decision, true);
                $this->outputMessage($input, $output, 'Save decision : ' . $decision->getId()
                    . ' status to : ' . $decision->getStatus());

                // if (!$input->getOption(self::OPTIONS['output'])) {
                //     $output->writeln('Save decision : ' . $decision->getId()
                //         . ' status to : ' . $decision->getStatus());
            } else {
                // if (!$input->getOption(self::OPTIONS['output'])) {
                    // $output->writeln('Change decision : ' . $decision->getId()
                    //     . ' status to : ' . $decision->getStatus());

                        $this->outputMessage($input, $output, 'Change decision : ' . $decision->getId()
                        . ' status to : ' . $decision->getStatus());
                // }
            }
        }

        $decisions = $decisionRepository->findByConflict();

        foreach ($decisions as $decision) {
            $pourcentValidation = (int)(($decision['sumApproved'] / $decision['countApproved']) * 100);

            if ($pourcentValidation >= 50) {
                $decision[0]->setStatus(Decision::STATUS_DONE);
            } else {
                $decision[0]->setStatus(Decision::STATUS_UNDONE);
            }

            if ($input->getOption('save')) {
                $decisionRepository->save($decision[0], true);
                $this->outputMessage($input, $output, 'Save decision : ' . $decision[0]->getId() .
                ' status to : ' . $decision[0]->getStatus() . ' with pourcent : ' . $pourcentValidation . '%');

                // if (!$input->getOption(self::OPTIONS['output'])) {
                //     $output->writeln('Save decision : ' . $decision[0]->getId() .
                //         ' status to : ' . $decision[0]->getStatus() .
                // ' with pourcent : ' . $pourcentValidation . '%');
                // }
            } else {
                $this->outputMessage($input, $output, 'Change decision : ' . $decision[0]->getId() .
                        ' status to : ' . $decision[0]->getStatus() . ' with pourcent : ' . $pourcentValidation . '%');

                // if (!$input->getOption(self::OPTIONS['output'])) {
                //     $output->writeln('Change decision : ' . $decision[0]->getId() .
                //         ' status to : ' . $decision[0]->getStatus() . ' with pourcent :
                // ' . $pourcentValidation . '%');
                // }
            }
        }

        return Command::SUCCESS;
    }
}
