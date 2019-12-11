<?php

declare(strict_types=1);

namespace WTG\Console\Commands\Admin\User;

use WTG\Models\Admin;
use Illuminate\Console\Command;
use WTG\Contracts\Models\AdminContract;

/**
 * Admin user change password command.
 *
 * @package     WTG\Console
 * @subpackage  Commands
 * @author      Thomas Wiringa  <thomas.wiringa@gmail.com>
 */
class ChangePassword extends Command
{
    const MIN_USERNAME_LENGTH = 3;
    const MIN_PASSWORD_LENGTH = 5;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:user:change-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change the password of an admin account';

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

        /** @var Admin $admin */
        $admin = app()->make(AdminContract::class)->where('username', $username)->first();

        if (! $admin) {
            $this->error('No admin user was found with the given username.');

            return;
        }

        $password = $this->secret('Password (input will be hidden)');

        if (! $password || strlen($password) < self::MIN_PASSWORD_LENGTH) {
            $this->error(__('The password must be at least :length characters long.', ['length' => self::MIN_PASSWORD_LENGTH]));

            return;
        }

        $admin->setPassword(bcrypt($password));
        $admin->saveOrFail();

        $this->output->success(__('The password has been changed successfully.'));
    }
}
