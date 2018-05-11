<?php

namespace WTG\Console\Commands\Admin\User;

use WTG\Models\Admin;
use Illuminate\Console\Command;
use WTG\Contracts\Models\AdminContract;

/**
 * Admin user create command.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class Create extends Command
{
    const MIN_USERNAME_LENGTH = 3;
    const MIN_PASSWORD_LENGTH = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:user:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin account';

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
     * @return void
     * @throws \Throwable
     */
    public function handle(): void
    {
        $username = $this->ask('Username');
        $password = $this->secret('Password (input will be hidden)');

        if (! $username || strlen($username) < self::MIN_USERNAME_LENGTH) {
            $this->error(__('The username must be at least :length characters long.', ['length' => self::MIN_USERNAME_LENGTH]));

            return;
        }

        if (! $password || strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $this->error(__('The password must be at least :length characters long.', ['length' => self::MIN_PASSWORD_LENGTH]));

            return;
        }

        /** @var Admin $admin */
        $admin = app()->make(AdminContract::class);
        $admin->setUsername($username);
        $admin->setPassword(bcrypt($password));
        $admin->saveOrFail();

        $this->output->success(__('The admin user has been created successfully.'));
    }
}
