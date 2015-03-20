## Sync your team page with HipChat

Our team at [Crew](https://pickcrew.com) has doubled in the last few months and we didn’t want to keep updating our Team page every time someone joined. Because we use [HipChat](https://www.hipchat.com) as our communication tool, we decided to use the HipChat API to manage our Team page. Once we update HipChat with a new team member our Team page automatically updates too.

If you want to do something like this for your Team page, we created HipTeam, a CakePHP plugin that will pull information (name, photo, and current status) from HipChat and display it on your Team page.

It looks like this:

![Crew Team page using HipTeam plugin](https://camo.githubusercontent.com/60453fdf85baa2664082b2d19fddc88fdca61ff0/68747470733a2f2f73332e616d617a6f6e6177732e636f6d2f75706c6f6164732e686970636861742e636f6d2f36383335352f313231323931372f4367484c487a73546e4430746c33762f73637265656e636170747572652d7069636b637265772d636f6d2d7465616d2e6a7067 "Crew Team page")

As a company, we try to be as transparent as possible. We want to help share things we’re learning that might be helpful for other people building their companies.

[Our investor updates](http://backstage.pickcrew.com/october-2014-investor-update/) are public.

[The process for building our product](http://backstage.pickcrew.com/building-in-public/) is public.

So we thought, why not share what each member of our team is currently working on and when in public? It gives a glimpse of how things get done in our company. Some of us early risers and some of us prefer working late into the night. You can have a look [here](https://pickcrew.com/team).

### 1. Before starting anything

Here's a list of things that you should know before doing anything else:

*   This plugin has been developed using **[CakePHP 2.6.2](http://cakephp.org/changelogs/2.6.2)**.
*   Every **paths** will always be relative to your application root (Where you installed CakePHP).

### 2. Setting up your CakePHP application

It's pretty easy to add this plugin to your current application. All you need to do is:

* **Clone the plugin repo:**

	In order to do so, run `git clone [GITHUB REMOTE]` and add the files to your plugin folder, which would be something like `app/Plugin/`

* **Load the plugin inside `app/Config/bootstrap.php` by adding this:**

	```php
	/** Come on guys, let's load this awesome plugin in our application */
	CakePlugin::load('HipTeam', array('bootstrap' => true));
	```

	> **Note:**
	>
	> The `bootstrap => true` attribute tells CakePHP that the plugin is actually using its own `bootstrap` file.

* **Add your API key to your `bootstrap.php` file, located at `app/Config/bootstrap.php`.**

	```php
	// Setting up the HipChat API key
	Configure::write('HipTeam.API_KEY', '');
	```

	> **Note:**
	>
	> You can find some code samples inside `app/Plugin/HipTeam/Config/bootstrap.php`. Those can easily being copied/pasted inside your `bootstrap.php`.

	If you don't know how to get a key, have a look [here](https://www.hipchat.com/docs/apiv2)

### 3. Display your team on one of your application pages

Before displaying anything, you need to generate the cache, to do so, you'll need to run the included shell located at `app/Plugin/HipTeam/Console/Command/TeamShell.php`, with this simple command: `app/Console/cake HipTeam.Team`.

This will generate the cache for you, have a look [here](#caching-api-requests) if you want to know how to customize the cache (path, duration, engine, ...)

HipTeam comes with a basic view, displaying information about each of your team members like `names` and `@mention names`.

> **Note:**
>
> If no `layout` has been set, the default one will be used which is located at `app/View/Layout/default.ctp`.

By default, that page will be available at this **url**: `hipteam/teams/display`. If this displays a 404, it's probably because you disabled the default CakePHP routes, so you'll need to create a new one.

To create a new route, you might want to add something like this to `app/Config/routes.php`:

```php
// Team Page
Router::connect('/team', array('plugin' => 'HipTeam', 'controller' => 'teams', 'action' => 'display'));
```

### 4. Extra cool features that you absolutely want to use

If you've read the previous steps, you're pretty much done with the basic usage, but you might want to take a look at what could be done to customize and improve the way HipTeam works.

#### Caching API requests

If you want to optimize the performance and avoid busting HipChat API rates (100 requests/5 minutes), you'll probably want to cache the API requests. It's really simple, as we took care of this for you, you just need to add this sample code to `app/Config/bootstrap.php`. The sample code is also located at `app/Plugin/HipTeam/Config/bootstrap.php`.

```php
Cache::config('hipteam_team', array(
    'engine' => 'File', // This can have different values depending on how you want to cache datas
    'duration' => '+5 minutes',
    'mask' => 0666,
    'path' => CACHE .'hipteam' . DS
));
```

#### Use the Shell as a CRON job

The shell has been designed to be used as a CRONjob to refresh the cache at a given interval. If you want to know how to use it as CRONjob, have a look [here](http://book.cakephp.org/2.0/en/console-and-shells/cron-jobs.html).

#### Extending the plugin

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

If you want to customize the actual design of the page, you should override the plugin's view. It's really simple to do it, you'll have to create a new view file inside `app/View/Plugin/HipTeam/Teams` and name your view according to the controller's method, which defaults to `display`. You can have a look at [CakePHP's documentation](http://book.cakephp.org/2.0/en/plugins/how-to-create-plugins.html#overriding-plugin-views-from-inside-your-application) as well.

**NB**: If you have overriden the plugin's controller with one of your own, **you don't have to override** the plugin's view but instead create a new view according to your controller. So if your controller is `EmployeesController`, you'll have to create a new view at `app/View/Employees` and name your view with your method's name.

### 5. Contributing

If you want to contribute, follow these steps:

*   Fork the repo by clicking the **Fork** button on the top-right corner
*   Always keep your code synced with the main repo
*   Pull request into the main branch

That's it, enjoy! If you have any questions, feel free to contact us at [support@pickcrew.com](mailto:support@pickcrew.com?Subject=About%20the%20HipTeam%plugin)
