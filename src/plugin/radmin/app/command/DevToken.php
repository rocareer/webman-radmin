<?php

namespace app\command;

use plugin\radmin\support\token\Token;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand('dev:token', 'dev token')]
class DevToken extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('name', InputArgument::OPTIONAL, 'Name description');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');
        $output->writeln('Hello dev:token');

        $token = 'P3nP5cLURe!RQeOTzjNPAVNEs&8a4(2o$Eh(nsT3IAOtyLf9te1tAlyLx45gWLMaN5kxnx1C&wrWcf)xTxi97bGhrBADum0EF$EP_E$F503bT^Srq8vP6Vuh%4&wgHohI8LGX@dksEKnOQW#Gv181_Tp@dwXtO*5HNlv)RdS06k2DGI+V24$jA%i!gL$EGw16%i_szlLDyP!CLyzkr(ygy8mXxLr)aXXurMln5H)W4___EfNYOIoSH7W1HNor&lS"';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsInR5cGUiOiJhY2Nlc3MiLCJyb2xlIjoiYWRtaW4iLCJpc3MiOiJSYWRtaW4iLCJqdGkiOiJhY2Nlc3MtZjcyY2E4MjdlZTEzODJmZTYwMDJiMjBhYTk2ZWMxZDhlOTVhNWU2ZmJlYjk0ZmUzMjE3MzA1YTI1MmU4Njk1NSIsImlhdCI6MTc0Njk2NjAzMSwiZXhwIjoxNzQ2OTY2MDQxfQ.bIiI-ng8uRPtRzKxPxrYoe43rNmS3nUpnV5MEvSA5zc';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsInR5cGUiOiJhY2Nlc3MiLCJyb2xlIjoiYWRtaW4iLCJpc3MiOiJSYWRtaW4iLCJqdGkiOiJhY2Nlc3MtNDc3MmRjNGNjNGY3MmZlZTA3NDc0MTUwMDVlYjk1MGUxNjdkM2RjYWI5OTY3YTQ2ZDYxMjIwMGMwZGI4ZmIwYSIsImlhdCI6MTc0Njk2NjYxOCwiZXhwIjoxNzQ2OTY2NjI4fQ.4MnkKjlPsjLhzpRt7NAOIJtlsEwmSPQTTIamEYzkz2U';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJSYWRtaW4iLCJzdWIiOjEsImlhdCI6MTc0Njk3MzcwMywianRpIjoiYWNjZXNzIiwiZXhwIjoxNzQ3NTc4NTAzLCJyb2xlcyI6W10sInJvbGUiOiJhZG1pbiIsInR5cGUiOiJyZWZyZXNoIn0.uI154w4Yk9sXIbREShmLUODO0wsx-4VhAI5mTSpngI4';
        $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJSYWRtaW4iLCJzdWIiOjEsImlhdCI6MTc0Njk4NDM4MiwianRpIjoiYWNjZXNzIiwiZXhwIjoxNzQ2OTg0MzkyLCJyb2xlcyI6W10sInJvbGUiOiJ1c2VyIiwidHlwZSI6ImFjY2VzcyJ9.9vd_hVeITf4VHioIKEqPLY_ig0dfcrhQFs8eeYZszIM"';
        Token::verify($token);
        echo Token::ttl($token, null);
        return self::SUCCESS;
    }

}
