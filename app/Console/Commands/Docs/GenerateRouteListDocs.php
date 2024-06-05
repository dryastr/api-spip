<?php

namespace App\Console\Commands\Docs;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class GenerateRouteListDocs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'docs:generate-route-list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate list of routes documentation';

    /**
     * @var array<int>
     */
    protected array $maxColumnCharacter = [
        'http_method' => 0,
        'route' => 0,
        'name' => 0,
        'middleware' => 0,
        'action' => 0,
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $routes = Route::getRoutes();
        $this->countMaxColumnCharacter($routes);

        $docs = '';
        foreach ($routes as $value) {
            $docs .= $this->generateDocs([
                'method' => implode(', ', $value?->methods ?? []),
                'middleware' => implode(',', $value?->action['middleware'] ?? []),
                'uri' => $value->uri() ?? '',
                'routeName' => $value->getName() ?? '',
                'actionName' => $value->getActionName() ?? '',
            ]);
        }
        $header = $this->generateHeader();
        $docs = "{$header}{$docs}";

        File::put('./docs/route.md', $docs);
    }

    /**
     * Generate .md header
     *
     * @return string
     */
    private function generateHeader(): string
    {
        $docs_title = "<!-- Route List -->\n";

        $method = $this->docsStrPad('HTTP Method', $this->maxColumnCharacter['http_method']);
        $uri = $this->docsStrPad('Route', $this->maxColumnCharacter['route']);
        $routeName = $this->docsStrPad('Name', $this->maxColumnCharacter['name']);
        $middleware = $this->docsStrPad('Middleware', $this->maxColumnCharacter['middleware']);
        $actionName = $this->docsStrPad('Action', $this->maxColumnCharacter['action']);

        $docs_header = "| {$method} | ${uri} | {$routeName} | {$middleware} | {$actionName} |\n";
        $docs_header_separator = $this->generateHeaderSeparator();

        return "{$docs_title}{$docs_header}{$docs_header_separator}";
    }

    /**
     * Generate header separator
     *
     * @return string
     */
    private function generateHeaderSeparator(): string
    {
        $separator = '|';
        foreach ($this->maxColumnCharacter as $value) {
            $repeater = str_repeat('-', $value + 2);
            $separator .= "{$repeater}|";
        }

        return "{$separator}\n";
    }

    /**
     * Update max column character for pretty .md
     *
     * @param $array
     *
     * @return void
     */
    private function updateMaxColumnCharacter($array): void
    {
        foreach ($array as $key => $value) {
            $length = strlen($value);
            $this->maxColumnCharacter[$key] = max($this->maxColumnCharacter[$key], $length);
        }
    }

    /**
     * Count max column character
     *
     * @param $routes
     *
     * @return void
     */
    private function countMaxColumnCharacter($routes): void
    {
        foreach ($routes as $value) {
            $method = implode(', ', $value?->methods);
            $middleware = implode(',', $value?->action['middleware'] ?? []);
            $uri = $value->uri() ?? '';
            $routeName = $value->getName() ?? '';
            $actionName = $value->getActionName() ?? '';

            $this->updateMaxColumnCharacter([
                'http_method' => $method,
                'route' => $uri,
                'name' => $routeName,
                'middleware' => $middleware,
                'action' => $actionName,
            ]);
        }
    }

    /**
     * Generate docs
     *
     * @param $array
     *
     * @return string
     */
    private function generateDocs($array): string
    {
        $method = $this->docsStrPad($array['method'], $this->maxColumnCharacter['http_method']);
        $uri = $this->docsStrPad($array['uri'], $this->maxColumnCharacter['route']);
        $routeName = $this->docsStrPad($array['routeName'], $this->maxColumnCharacter['name']);
        $middleware = $this->docsStrPad($array['middleware'], $this->maxColumnCharacter['middleware']);
        $actionName = $this->docsStrPad($array['actionName'], $this->maxColumnCharacter['action']);

        return "| {$method} | {$uri} | {$routeName} | {$middleware} | {$actionName} |\n";
    }

    /**
     * Docs string pad
     *
     * @param $string
     * @param $length
     *
     * @return string
     */
    private function docsStrPad($string, $length): string
    {
        return strlen($string) < $length ? str_pad($string, $length) : $string;
    }
}
