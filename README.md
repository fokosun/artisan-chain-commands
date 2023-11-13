### Chain Commands Artisan Command
This package helps to chain multiple artisan commands using a simple, easy to understand syntax.

### Example
```text
php artisan chain-commands "make:controller TestController,make:event TestEvent" 
```

This will run the make:controller and make:event artisan commands consecutively and displays the output in a table.


## How to install
Run the following artisan command in the root of your Laravel project.
```text
composer require fokosun/artisan-chain-commands 
```
Lastly, add Artisan Chain Commands to the list of providers in `config/app` under providers:
```text
'providers' => ServiceProvider::defaultProviders()->merge([
   ...
    \Fokosun\ArtisanChainCommands\Providers\ArtisanChainCommandsProvider::class,
    ...
])->toArray(),
```
## How to use
You can chain multiple artisan commands with ease but there are a few commands that are not supported. See list below.

```text
'chain-commands' => 'Not allowed',
'db' => "This opens a REPL (Read, Evaluate, Print and Loop) environment.\nYou should not run this command with chain commands.",
'docs' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
'docs 1' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
'docs 2' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
'docs -- search query' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
'docs -- search query here' => "This opens the Laravel documentation page in a browser window.\nYou should not run this command with chain commands.",
'help' => "Displays help for a command.\nYou should not run this command with chain commands.",
'serve' => 'Not allowed',
'test' => 'Not allowed.',
'tinker' => "This opens a REPL (Read, Evaluate, Print and Loop) environment.\nYou should not run this command with chain commands.",
'vendor:publish'  => "This is an interactive command.\nYou should not run this command with chain commands.",
'schedule:work' => "Starts the schedule worker.\nYou should not run this command with chain commands.",
'queue:work' => "Starts processing jobs on the queue as a daemon.\nYou should not run this command with chain commands.",
'queue:restart' => "Restarts queue worker daemons after their current job.\nYou should not run this command with chain commands.",
'queue:listen' => "Listens to a given queue.\nYou should not run this command with chain commands."
```

Artisan chain command will ignore these commands for the reasons outlined in the table above.

## Shorthand commands
Artisan chain commands also ship with a few shorthand commands which are essentially a chain of commonly used commands to aid development. An example are the `'config:clear', 'cache:clear', 'view:clear'` commands combination. These can be run with just one single shorthand command. See list below:
```text
'clear:ccv' => ['config:clear', 'cache:clear', 'view:clear'],
'clear:*' => ['config:clear', 'cache:clear', 'view:clear','event:clear', 'optimize:clear', 'queue:clear'],
'db:rms' => ['migrate:refresh', 'migrate', 'db:seed'],
```

## Contributing
This is opensource and contributions are highly welcome.

## License
MIT
