<?php declare(strict_types=1);

/**
 * Checks if bash can be invoked.
 *
 * Credit to Troels Knak-Nielsen
 * (http://www.sitepoint.com/interactive-cli-password-prompt-in-php/) for
 * inspiring this code.
 *
 * @return bool
 */
if (!function_exists("can_invoke_bash")) {
    function can_invoke_bash() : bool
    {
        return (strcmp(trim(shell_exec("/usr/bin/env bash -c 'echo OK'")), 'OK') === 0);
    }
}

/**
 * Checks if script is running as root user
 *
 * @return bool
 */
if (!function_exists("is_su")) {
    function is_su() : bool
    {
        $userinfo = posix_getpwuid(posix_geteuid());
        return (bool)($userinfo['uid'] == 0 || $userinfo['name'] == 'root');
    }
}

/**
 * Convienence method for determining if a flag constant is set
 *
 * @return boolean true if the flag is set
 */
 if (!function_exists("is_flag_set")) {
     function is_flag_set($flags, $flag) : bool
     {
         // Flags support bitwise operators so it's easy to see
         // if one has been set.
         return ($flags & $flag) == $flag;
     }
 }
