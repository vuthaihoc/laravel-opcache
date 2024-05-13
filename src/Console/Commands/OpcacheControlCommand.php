<?php

namespace HocVT\LaravelOpcache\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;

class OpcacheControlCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'opcache {action=status : action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Control OPCache';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        $response = $this->sendRequest($action);
        $response->throw();
        $body = $response->body();
        $this->warn('Result:');
        $this->info($body);
    }

    protected function sendRequest($action = 'status')
    {
        $url = URL::signedRoute('laravel.opcache.control', ['action' => $action, 'format' => 'cli'], now()->addSeconds(60), false);
        $url = rtrim(config('opcache.url'), '/').$url;

        return Http::withHeaders(config('opcache.headers'))
            ->withOptions(['verify' => config('opcache.verify')])
            ->get($url);
    }
}
