<?php
/**
 * Copyright (c) 2010, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the <organization> nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package vcsstats
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * This is necessary since the autoloader is not configured yet when this class
 * is used.
 */
require_once 'VcsStats/Runner/Abstract.php';

/**
 * Command line runner
 */
class VcsStats_Runner_Cli extends VcsStats_Runner_Abstract
{
    /**
     * Command line argument parser
     *
     * @var ezcConsoleInput
     * @see http://ezcomponents.org/docs/api/trunk/ConsoleTools/ezcConsoleInput.html
     */
    public static $consoleInput;

    /**
     * object used for display in command line
     *
     * @var ezcConsoleOutput
     * @see http://ezcomponents.org/docs/api/trunk/ConsoleTools/ezcConsoleOutput.html
     */
    public static $consoleOutput;

    /**
     * Main function. Sets up the environment and coordinate the work.
     */
    public static function run()
    {
        // Set autoload up
        require_once 'VcsStats/Loader.php';
        spl_autoload_register(array('VcsStats_Loader', 'autoload'));
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
            self::displayVersion();
            exit(0);
        }

        $arguments = $input->getArguments();
        if (1 !== count($arguments)) {
            self::displayError('Path to repository is missing', 'error');
            exit(1);
        }
        $repositoryPath = $arguments[0];

        // Do the actual work
        self::displayVersion();

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
            self::displayError($exception->getMessage());
            exit(1);
        }
    }

    /**
     * Display debug informations
     *
     * @var string $message Message to display
     */
    public static function displayDebug($message)
    {
        self::displayMessage($message, 'debug');
    }

    /**
     * Display errors
     *
     * @var string $message Message to display
     */
    public static function displayError($message)
    {
        self::displayMessage($message, 'error');
    }

    /**
     * Display help informations
     */
    public static function displayHelp()
    {
        self::displayVersion();
        echo "\n";
        echo "Help message...\n\n";
    }

    /**
     * Display messages
     *
     * @var string $message Message to display
     * @var string $type    Type of the message
     */
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

    /**
     * Display version informations
     */
    public static function displayVersion()
    {
        self::$consoleOutput->outputLine(
            'vcsstats 0.1-dev by Jean-Marc Fontaine',
            'version'
        );
    }
}