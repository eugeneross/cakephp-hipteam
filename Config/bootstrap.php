<?php
/**
 * Here's the plugin's bootstrap file.
 * This is just an example of which configuration can be added for the plugin.
 * There are three things you should add to you own bootstrap file (app/Config/bootstrap.php).
 * - HipChat API key
 * - Cache feature
 * - Replace the default HipChat's profile picture
 */

/**
 * You need to provide an API key to be able to use HipChat's API.
 * Copy/paste this code into your own bootstrap file and add your API key.
 *
 * // Setting up the HipChat API key
 * Configure::write('HipTeam.API_KEY', '');
 */

/**
 * Cache configuration for the plugin
 * If you want to apply a custom caching system, you should take this, put it in your app's bootstrap file and customize it to fit your application.
 * @link http://book.cakephp.org/2.0/en/core-libraries/caching.html
 *
 * // Cache HipChat API requests
 * Cache::config('hipteam_team', array(
 *  'engine' => 'File', // This can have different values depends on how you want to cache datas
 *  'duration' => '+10 minutes', // How long do you want to cache datas? Don't forget the plugin shell can refresh this cache :)
 *  'mask' => 0666,
 *  'path' => CACHE .'hipteam' . DS // The path where the cache should be stored, usually in app/tmp/
 * ));
 */

/**
 * HipChat has a default profile picture, which is shown where the team member doesn't have any.
 * But it's not very nice, so if you want a different one, just copy/paste this code and change the path.
 *
 * // Change the default HipChat's profile picture
 * Configure::write('HipTeam.DEFAULT_PROFILE_PICTURE', '');
 */
