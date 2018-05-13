<?php

use Illuminate\Database\Seeder;

use App\Job;
use App\Task;
use App\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeded users.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $users;

    /**
     * User created jobs.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $jobs;

    /**
     * Seed functions.
     *
     * @var array
     */
    protected $seeds = [
        'migrate',
        'users',
        'jobs',
        'tasks',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->seeds as $seed) {
            $this->command->line("Processing: {$seed}");
            call_user_func([$this, $seed]);
        }
    }

    /**
     * (Re)Migrate tables.
     *
     * @return void
     */
    public function migrate()
    {
        $this->command->call('migrate:reset');
        $this->command->call('migrate');
        $this->command->call('passport:install');
        $this->command->line('Migrated tables.');
    }

    /**
     * Seed users.
     *
     * @return void
     */
    public function users()
    {
        $this->users = factory(User::class, 10)->create();
        $this->users[0]->email = 'test@lighthouse.com';
        $this->users[0]->save();

        $this->command->line('Seeded users');
    }

    /**
     * Seed jobs.
     *
     * @return void
     */
    public function jobs()
    {
        $this->jobs = collect();

        $this->users->each(function (User $user) {
            $data = ['user_id' => $user->id];

            factory(Job::class, 3)->create($data)->each(function (Job $job) {
                $this->jobs->push($job);
            });
        });

        $this->command->line('Seeded jobs');
    }

    /**
     * Seed tasks.
     *
     * @return void
     */
    public function tasks()
    {
        $this->jobs->each(function (Job $job) {
            $data = ['job_id' => $job->id];

            factory(Task::class, 3)->create($data)->each(function (Task $task) {
                $users = $this->users->random(3);

                $task->users()->attach($users->pluck('id')->toArray(),['pivot_field'=>'string_'.rand(1,6)]);
            });
        });

        $this->command->line('Seeded tasks');
    }
}
