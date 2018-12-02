<?php
namespace CLILib;

class Prompt
{
    const FLAG_SILENT = 0x001;

    /**
     * Convienence method for determining if a FLAG_* constant is set
     *
     * @return boolean true if the flag is set
     */
    protected static function isFlagSet($flags, $flag)
    {
        // Flags support bitwise operators so it's easy to see
        // if one has been set.
        return ($flags & $flag) == $flag;
    }

    /**
     * This function waits for input from $target (default is STDIN). Support
     * silent input by setting $silent=true However this requires bash. If
     * bash is not available, then it will trigger a E_USER_NOTICE error and
     * fallback to the "non-silent" method.
     *
     * Credit to Troels Knak-Nielsen
     * (http://www.sitepoint.com/interactive-cli-password-prompt-in-php/) for
     * inspiring most of this code.
     *
     * @param  string $prompt
     *                        This is displayed before reading any input.
     * @param  bool   $silent
     *                        Turns off echoing of input to CLI. Useful
     *                        for passwords. Only works if bash is avilable.
     * @return string
     */
    public static function display($prompt, $flags = null, $default = null, \Closure $validator = null, $character = ":", $target=STDIN)
    {
        $silent = self::isFlagSet($flags, self::FLAG_SILENT);

        if ($silent == true && !self::canInvokeBash()) {
            trigger_error("bash cannot be invoked from PHP so 'silent' flag cannot be used.", E_USER_NOTICE);
            $silent = false;
        }

        if (!($prompt instanceof Message)) {
            $prompt = new Message($prompt);
        }

        $prompt->message(sprintf(
            "%s%s%s ",
            $prompt->message,
            (!is_null($default) ? " [{$default}]" : null),
            $character
        ));

        do {
            $prompt
                ->appendNewLine(false)
                ->display()
            ;

            if ($silent) {
                if ($target != STDIN) {
                    throw new \Exception("cannot use silent prompt when target is not STDIN");
                }

                $command = "/usr/bin/env bash -c 'read -s in && echo \$in'";
                $input = shell_exec($command);
                echo PHP_EOL;
            } else {
                $input = fgets($target, 256);
            }

            $input = trim($input);
            if (strlen(trim($input)) == 0 && !is_null($default)) {
                $input = $default;
            }
        } while ($validator instanceof \Closure && !$validator($input));

        return $input;
    }

    /**
     * Checks if bash can be invoked.
     *
     * Credit to Troels Knak-Nielsen
     * (http://www.sitepoint.com/interactive-cli-password-prompt-in-php/) for
     * inspiring this code.
     *
     * @return bool
     */
    protected static function canInvokeBash()
    {
        return (strcmp(trim(shell_exec("/usr/bin/env bash -c 'echo OK'")), 'OK') === 0);
    }
}
