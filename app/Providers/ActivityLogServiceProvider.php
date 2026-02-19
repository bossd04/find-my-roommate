<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Log;

class ActivityLogServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register the activity logger as a singleton
        $this->app->singleton('activity', function ($app) {
            return new class {
                /**
                 * Log an activity.
                 */
                public function log(string $description, $subject = null, array $properties = [], string $event = null)
                {
                    try {
                        return ActivityLog::log($description, $subject, $properties, $event);
                    } catch (\Exception $e) {
                        Log::error('Failed to log activity: ' . $e->getMessage());
                        return null;
                    }
                }

                /**
                 * Log an activity for a specific user.
                 */
                public function forUser($user)
                {
                    return new class($user) {
                        protected $user;

                        public function __construct($user)
                        {
                            $this->user = $user;
                        }

                        public function log(string $description, $subject = null, array $properties = [], string $event = null)
                        {
                            try {
                                return ActivityLog::create([
                                    'log_name' => config('activitylog.default_log_name', 'default'),
                                    'description' => $description,
                                    'subject_type' => $subject ? get_class($subject) : null,
                                    'subject_id' => $subject ? $subject->id : null,
                                    'causer_type' => get_class($this->user),
                                    'causer_id' => $this->user->id,
                                    'properties' => $properties,
                                    'event' => $event,
                                    'ip_address' => request()->ip(),
                                    'user_agent' => request()->userAgent(),
                                    'method' => request()->method(),
                                    'route' => request()->path(),
                                ]);
                            } catch (\Exception $e) {
                                Log::error('Failed to log activity: ' . $e->getMessage());
                                return null;
                            }
                        }
                    };
                }

                /**
                 * Get the activity logger instance.
                 */
                public function getLogger()
                {
                    return $this;
                }

                /**
                 * Log an activity with the given description.
                 */
                public function __call($method, $parameters)
                {
                    if ($method === 'log') {
                        return $this->log(...$parameters);
                    }

                    throw new \BadMethodCallException("Method [{$method}] does not exist.");
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register the activity helper function
        if (!function_exists('activity')) {
            function activity()
            {
                return app('activity');
            }
        }
    }
}
