# config/deploy.rb file
lock '3.4.0'

set :application, "rogue"
set :repo_url, 'git@github.com:DoSomething/rogue.git'

set :user, "dosomething"
set :group, "dosomething"
set :use_sudo, false

set :branch, "master"
if ENV['branch']
    set :branch, ENV['branch'] || 'master'
end

set :keep_releases, 1

set :npm_flags, ''
set :composer_install_flags, '--no-dev --optimize-autoloader'

set :linked_files, %w{.env}
set :linked_dirs, %w{images storage/logs storage/dumps storage/keys storage/system}

namespace :deploy do
  task :restart_queue_worker, :on_error => :continue do
    run "ps -ef | grep 'queue:work' | awk '{print $2}' | xargs sudo kill -9"
  end
end

after "deploy:restart_queue_worker"
