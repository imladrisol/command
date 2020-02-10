<?php

interface Command
{
    public function execute();
}

interface UndoableCommand extends Command
{
    public function undo();

    public function redo();
}

class MakePizza implements Command
{
    private Receiver $output;

    public function __construct(Receiver $console)
    {
        $this->output = $console;
    }

    public function execute()
    {
        $this->output->write('pizza');
    }
}

class AddCheese implements UndoableCommand
{
    private Receiver $output;

    public function __construct(Receiver $console)
    {
        $this->output = $console;
    }

    public function execute()
    {
        $this->output->execute('cheese');
    }

    public function undo()
    {
        $this->output->undo('cheese');
    }

    public function redo()
    {
        $this->output->redo('cheese');
    }
}

class AddMushroom implements UndoableCommand
{
    private Receiver $output;

    public function __construct(Receiver $console)
    {
        $this->output = $console;
    }

    public function execute()
    {
        $this->output->execute('mushroom');
    }

    public function undo()
    {
        $this->output->undo('mushroom');
    }

    public function redo()
    {
        $this->output->redo('mushroom');
    }
}

class AddBacon implements UndoableCommand
{
    private Receiver $output;

    public function __construct(Receiver $console)
    {
        $this->output = $console;
    }

    public function execute()
    {
        $this->output->execute('bacon');
    }

    public function undo()
    {
        $this->output->undo('bacon');
    }

    public function redo()
    {
        $this->output->redo('bacon');

    }
}

class Receiver
{
    private array $output = [];

    public function write($key)
    {
        $this->output[$key] = $key;
    }

    public function getOutput(): string
    {
        return "\n Output: \n" . implode(', ', $this->output);
    }

    public function execute($key)
    {
        $this->output[$key] = $key;
        $this->getOutput();
    }

    public function undo($key)
    {
        if (in_array($key, $this->output)) {
            unset($this->output[$key]);
        }
    }

    public function redo($key)
    {
        $this->output[$key] = $key;
    }
}

class Invoker
{
    private Command $command;

    public function setCommand(Command $cmd)
    {
        $this->command = $cmd;
    }

    public function run()
    {
        $this->command->execute();
    }
}


$invoker = new Invoker();
$receiver = new Receiver();

$invoker->setCommand(new MakePizza($receiver));
$invoker->run();

echo $receiver->getOutput() . "\n\n";

$messageAddCheese = new AddCheese($receiver);
$messageAddCheese->execute();

$invoker->run();
echo $receiver->getOutput();

$messageAddCheese->undo();
echo "\n";
echo "\n";
echo $receiver->getOutput();
echo "\n";
echo "\n";

$messageAddCheese->redo();

echo $receiver->getOutput();

$messageAddBacon = new AddBacon($receiver);
$messageAddBacon->execute();
$invoker->run();
echo $receiver->getOutput();
$messageAddBacon->undo();
echo $receiver->getOutput();
$messageAddBacon->redo();
echo $receiver->getOutput();
