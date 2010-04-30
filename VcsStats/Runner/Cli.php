<?php
class VcsStats_Runner_Cli
{
    public static $consoleInput;
    public static $consoleOutput;

    final private function __clone()
    {

    }

    final private function __construct()
    {

    }

    public static function autoload($class)
    {
        $path = str_replace('_', '/', $class) . '.php';
        if (file_exists($path)) {
            require_once $path;
            return true;
        } else {
            return false;
        }
    }

    public static function run()
    {
        // Set autoload up
        spl_autoload_register(array(__CLASS__, 'autoload'));
        require_once 'ezc/Base/base.php';
        spl_autoload_register(array('ezcBase', 'autoload'));

        // Set console output up
        $output = new ezcConsoleOutput();
        $output->formats->version->style = array('bold');
        $output->formats->debug->color   = 'yellow';
        $output->formats->debug->style   = array('italic');
        $output->formats->error->color   = 'red';
        self::$consoleOutput = $output;

        // Set console input up
        $input = new ezcConsoleInput();
        self::$consoleInput = $input;

        $debugOption = new ezcConsoleOption('d', 'debug');
        $debugOption->type      = ezcConsoleInput::TYPE_NONE;
        $debugOption->shorthelp = 'Display debugging informations';
        $debugOption->longhelp  = 'Display debugging informations';
        $input->registerOption($debugOption);

        $helpOption = new ezcConsoleOption('h', 'help');
        $helpOption->type      = ezcConsoleInput::TYPE_NONE;
        $helpOption->shorthelp = 'Display help';
        $helpOption->longhelp  = 'Display this help message';
        $input->registerOption($helpOption);

        $verboseOption = new ezcConsoleOption('v', 'verbose');
        $verboseOption->type      = ezcConsoleInput::TYPE_NONE;
        $verboseOption->shorthelp = 'Display processing informations';
        $verboseOption->longhelp  = 'Display processing informations';
        $input->registerOption($verboseOption);

        $versionOption = new ezcConsoleOption(null, 'version');
        $versionOption->type      = ezcConsoleInput::TYPE_NONE;
        $versionOption->shorthelp = 'Display version';
        $versionOption->longhelp  = 'Display version';
        $input->registerOption($versionOption);

        // Process console input
        try {
            $input->process();
        } catch (ezcConsoleOptionException $exception) {
            echo $exception->getMessage() . "\n";
            exit(1);
        }

        if ($input->getOption('help')->value) {
            self::displayHelp();
            exit(0);
        } else if ($input->getOption('version')->value) {
            self::displayVersionString();
            exit(0);
        }

        $arguments = $input->getArguments();
        if (1 !== count($arguments)) {
            self::displayFatalError('Path to repository is missing', 'error');
            exit(1);
        }
        $repositoryPath = $arguments[0];

        // Do the actual work
        self::displayVersionString();

        try {
            $options = array('path' => $repositoryPath);
            $wrapper = new VcsStats_Wrapper_Subversion($options);

            $cachePath = realpath(dirname(__FILE__) . '/../../tmp');
            $cache = new VcsStats_Cache($wrapper, $cachePath);
            $cache->updateData();

            $analyzer = new VcsStats_Analyzer($cache);
            $results  = $analyzer->analyze();

            $reporter = new VcsStats_Reporter_Text();
            $reporter->displayData($results);
        } catch (Exception $exception) {
            self::displayFatalError($exception->getMessage());
            exit(1);
        }
    }

    public static function displayDebug($message)
    {
        self::displayMessage($message, 'debug');
    }

    public static function displayFatalError($message)
    {
        self::displayVersionString();
        self::displayMessage($message, 'error');
    }

    public static function displayHelp()
    {
        self::displayVersionString();
        echo "\n";
        echo "Help message...\n\n";
    }

    public static function displayMessage($message, $type = 'info')
    {
        if ('info' == $type &&
            !self::$consoleInput->getOption('verbose')->value) {
            return;
        } elseif ('debug' == $type
                  && !self::$consoleInput->getOption('debug')->value) {
            return;
        }

        self::$consoleOutput->outputText("$message\n", $type);
    }

    public static function displayVersionString()
    {
        self::$consoleOutput->outputText(
            "vcsstats 0.1-dev by Jean-Marc Fontaine\n",
            'version'
        );
    }
}