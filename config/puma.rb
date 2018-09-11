pidfile    '/var/www/pids/puma.pid'
state_path '/var/www/pids/puma.state'
daemonize  false

threads_count = ENV.fetch('PUMA_MAX_THREADS') { 5 }

threads        threads_count, threads_count
workers        ENV.fetch('PUMA_WEB_CONCURRENCY') { 2 }
worker_timeout ENV.fetch('PUMA_WEB_WORKER_TIMEOUT') { 60 }.to_i

environment ENV.fetch('RAILS_ENV') { 'production' }
port        ENV.fetch('PUMA_PORT') { 3000 }

preload_app!

before_fork do
  ActiveSupport.on_load(:active_record) do
    ActiveRecord::Base.connection_pool.disconnect!
  end

  require 'puma_worker_killer'

  PumaWorkerKiller.config do |config|
    config.ram           = ENV['PUMA_MAX_RAM'].presence.to_i || 5000 # mb
    config.frequency     = 15    # seconds
    config.percent_usage = 0.95
    config.rolling_restart_frequency = 24 * 3600 # 12 hours in seconds
    config.reaper_status_logs = false # setting this to false will not log lines like:
    # PumaWorkerKiller: Consuming 54.34765625 mb with master and 2 workers.

    config.pre_term = -> (worker) { puts "Worker #{worker.index}(pid: #{worker.pid}) being killed" }
  end

  PumaWorkerKiller.start
end

on_worker_boot do
  ActiveSupport.on_load(:active_record) do
    ActiveRecord::Base.establish_connection
  end
end
