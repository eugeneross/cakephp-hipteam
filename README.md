## Sync your team page with HipChat ##
Because we wanted a quick and cool way to add new teammates on our team page, and because we are currently using [HipChat][hipchat_link], we decided to use their API to easily manage our team page. We created HipTeam which is a CakePHP plugin. So, if you're using [HipChat][hipchat_link] or want to use it, this plugin may be useful for you.

This is what we, at [Crew][crew_link], currently have for our [Team page][crew_team_link]. It helps our users know what the Crew team is currently working on. It also provides transparency so you can actually see who's online and who's not :)

![Crew Team page using HipTeam plugin](https://s3.amazonaws.com/uploads.hipchat.com/68355/1212917/oK4a2IApxgL7S80/screencapture-pickcrew-com-team.jpg "Crew Team page")

To give you an idea, we have a shell (as CRON job) that makes API requests to HipChat and caches them, so the view always reads data from the cache.


### 1. Before starting anything ###
Here's a list of things that you should know before doing anything else:

- This plugin has been developped using **[CakePHP 2.6.2][cakephp_version]**.
- Every **path** is relative to your application root (Where you installed CakePHP).

### 2. Setting up your CakePHP application ###
It's pretty easy to add this plugin to your current application. All you need to do is:

- **Clone the plugin repo:**
	In order to do so, run `git clone [GITHUB REMOTE]` and add the files to your plugin folder, which would be something like `app/Plugin/`

-  **Load the plugin inside `app/Config/bootstrap.php` by adding this:**

	```php
	/** Come on guys, let's load this awesome plugin in our application */
	CakePlugin::load('HipTeam', array('bootstrap' => true));
	```

	> **Note:**
	>
	> The `bootstrap => true` attribute tells CakePHP that the plugin is actually using its own `bootstrap` file.

-  **Add your API key to your `bootstrap.php` file, located at `app/Config/bootstrap.php`.**

	```php
	// Setting up the HipChat API key
	Configure::write('HipTeam.API_KEY', '');
	```

	> **Note:**
	>
	> You can find some code samples inside `app/Plugin/HipTeam/Config/bootstrap.php`. Those can easily being copied/pasted inside your `bootstrap.php`.

	If you don't know how to get a key, have a look [here][hipchat_api]

### 3. Display your team on one of your application pages ###
Before displaying anything, you need to generate the cache, to do so, you'll need to run the included shell located at `app/Plugin/HipTeam/Console/Command/TeamShell.php`, with this simple command: `app/Console/cake HipTeam.Team`.

HipTeam comes with the most basic view you can imagine, it's just there to display general information about your team, such as `names`, `@mention names`.

> **Note:**
>
> If no `layout` has been set, the default one will be used which is located at `app/View/Layout/default.ctp`.

By default, that page will be available at this **url**: `hipteam/teams/display`. If this displays a 404, it's probably because you disabled the default CakePHP routes, so you'll need to create a new one.

To create a new route, you might want to add something like this to `app/Config/routes.php`:

```php
// Team Page
Router::connect('/team', array('plugin' => 'HipTeam', 'controller' => 'teams', 'action' => 'display'));
```

### 4. Extra cool features that you absolutely want to use ###
If you've read the previous steps, you're pretty much done with the basic usage, but you might want to take a look at what could be done to customize and improve the way HipTeam works.

#### Caching API requests ####
If you want to optimize the performance and avoid busting HipChat API rates (100 requests/5 minutes), you'll probably want to cache the API requests. It's really simple, as we took care of this for you, you just need to add this sample code to `app/Config/bootstrap.php`. The sample code is also located at `app/Plugin/HipTeam/Config/bootstrap.php`.

```php
Cache::config('hipteam_team', array(
	'engine' => 'File', // This can have different values depending on how you want to cache datas
	'duration' => '+5 minutes',
	'mask' => 0666,
	'path' => CACHE .'hipteam' . DS
));
```

#### Use the Shell as a CRON job ####
The shell has been designed to be used as a CRONjob to refresh the cache at a given interval. If you want to know how to use it as CRONjob, have a look [here][cakephp_cronjob].

#### Extending the plugin ####
It's always a better practice to extend a plugin instead of modifying the core, because the core will be overriden when a plugin is updated. Extending a plugin is really easy. Here's an example if you want to extend the controller:

```php
// Tell the current controller that we want to use the HipTeamsController from the HipTeam plugin
App::uses('HipTeamsController', 'HipTeam.Controller');
class EmployeesController extends HipTeamsController {
	public $name = 'Employees';

/** Display online users */
	public function display() {
		# ...
	}
}
```

If you want to customize the actual design of the page, you should override the plugin's view. It's really simple to do it, you'll have to create a new view file inside `app/View/Plugin/HipTeam/Teams` and name your view according to the controller's method, which defaults to `display`. You can have a look at [CakePHP's documentation][cakephp_book_plugin] as well.

**NB**: If you have overriden the plugin's controller with one of your own, **you don't have to override** the plugin's view but instead create a new view according to your controller. So if your controller is `EmployeesController`, you'll have to create a new view at `app/View/Employees` and name your view with your method's name.


### 5. Contributing ###
If you want to contribute, follow these steps:

- Fork the repo by clicking the **Fork** button on the top-right corner
- Always keep your code synced with the main repo
- Pull request into the main branch

That's it, enjoy! If you have any questions, feel free to contact us at [support@pickcrew.com](mailto:support@pickcrew.com?Subject=About%20the%20HipTeam%plugin)

[crew_link]: https://pickcrew.com
[crew_team_link]: https://pickcrew.com/team
[hipchat_link]: https://www.hipchat.com
[hipchat_api]: https://www.hipchat.com/docs/apiv2
[hipchat_api_user]: https://www.hipchat.com/docs/apiv2/method/view_user
[cakephp_book]: http://book.cakephp.org/2.0/en/plugins/how-to-use-plugins.html#plugin-configuration
[cakephp_version]: http://cakephp.org/changelogs/2.6.2
[cakephp_book_plugin]: http://book.cakephp.org/2.0/en/plugins/how-to-create-plugins.html#overriding-plugin-views-from-inside-your-application
[cakephp_cronjob]: http://book.cakephp.org/2.0/en/console-and-shells/cron-jobs.html
