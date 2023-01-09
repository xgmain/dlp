<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ValidateController;
use App\Http\Requests\RulePostRequest;

class goalDlpRun extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'goal:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test DLP against Goal record';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = DB::table('goals')->count();
        $bar = $this->output->createProgressBar($count);

        DB::table('goals')->orderBy('id')->chunk(100, function ($goals) use($bar) {
            foreach ($goals as $goal) {
                $request = new RulePostRequest();
                $request->client_id = 1;
                $request->form_id = 11;
                $request->user_id = 111;
                $request->question_id = 101;
                $request->ip = "123.123.123.123";
                $request->text = $goal->body;
                $request->src = $goal->id;

                (new ValidateController)->post($request);
                $bar->advance();
            }
        });

        $bar->finish();
    }
}
