<?php
    namespace App\Command;

    use App\Exception\InvalidJsonException;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class ConvertCommand extends Command
    {
        protected static $defaultName = 'app:convert';

        /**
         * ConvertCommand constructor.
         */
        public function __construct()
        {
            parent::__construct();
        }

        protected function configure()
        {
            $this->setDescription('Flattens a JSON object to AppSettings ENV.');
            $this->addOption('json', 'j', InputOption::VALUE_REQUIRED, 'JSON blob to convert.');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         */
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            try
            {
                $blob = $input->getOption('json');
                if(false === $blob)
                {
                    throw new \InvalidArgumentException('`json` must be set to continue.');
                }
                try
                {
                    $json = json_decode($blob, true);
                    if ($json === null && json_last_error() !== JSON_ERROR_NONE)
                    {
                        throw new InvalidJsonException("JSON is invalid");
                    }
                }
                catch (\Exception $e)
                {
                    throw $e;
                }
                $results = [];
                if(!is_array($json)) $json = [$json];
                if($this->is_multi_array($json))
                {
                    foreach ($json as $key => $line)
                    {
                        $results[] = $this->squash($line, $key);
                    }
                    $result = array_merge(...$results);
                }
                else
                {
                    $result = $json;
                }

                $io->writeln(json_encode($result, JSON_UNESCAPED_SLASHES));
            }
            catch (\Exception $e){
                $io->error($e->getMessage());
                return 1;
            }
        }

        /**
         * @param $array
         * @param string $prefix
         * @param string $delimiter
         * @return array
         */
        private function squash($array, $prefix = '', $delimiter = "__")
        {
            $flat = array();

            if (!is_array($array)) return [$prefix=>$array];

            foreach($array as $key => $value)
            {
                $_key = ltrim($prefix.$delimiter.$key, $delimiter);

                if (is_array($value) || is_object($value))
                {
                    // Iterate this one too
                    $flat = array_merge($flat, $this->squash($value, $_key, $delimiter));
                }
                else
                {
                    $flat[$_key] = $value;
                }
            }

            return $flat;
        }

        /**
         * @param $arr
         * @return bool
         */
        private function is_multi_array( $arr ) {
            rsort( $arr );
            return isset( $arr[0] ) && is_array( $arr[0] );
        }

    }