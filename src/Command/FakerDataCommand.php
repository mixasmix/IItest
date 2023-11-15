<?php

namespace App\Command;

use App\Service\ManagerService;
use App\Service\OrderService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'setFakerData',
    description: 'Добавляет фейковые данные',
)]
class FakerDataCommand extends Command
{
    private array $managers;
    private array $orders;

    private Generator $faker;

    public function __construct(
        private readonly ManagerService $managerService,
        private readonly OrderService $orderService,
        private readonly EntityManagerInterface $entityManager,
    ) {
        parent::__construct();

        $this->faker = Factory::create('ru_RU');
    }

    protected function configure(): void
    {
        $this->addOption(
            name: 'orders',
            shortcut: null,
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Количество заказов',
            default: 50,
        );

        $this->addOption(
            name: 'managers',
            shortcut: null,
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Количество менеджеров',
            default: 30,
        );
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $managers = $input->getOption('managers');
        $orders = $input->getOption('orders');

        $this->createManagers($managers);
        $this->createOrders($orders);
        $this->entityManager->flush();

        $output->writeln('Заказы (' .  count($this->orders) . ') и менеджеры (' .  count($this->managers) . ') добавлены');

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function createManagers(int $quantity): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            $this->managers[] = $this->managerService->create(
                firstName: $this->faker->firstName,
                lastName: $this->faker->lastName,
                birthdate: new DateTimeImmutable($this->faker->date())
            );
        }
    }

    private function createOrders(int $quantity): void
    {
        for ($i = 0; $i < $quantity; $i++) {
            $this->orders[] = $this->orderService->create(
                manager: $this->faker->randomElement($this->managers),
                name: $this->faker->company
            );
        }
    }
}
