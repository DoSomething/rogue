# config/deploy.rb file
lock '3.4.0'

set :application, "rogue"
set :repo_url, 'git@github.com:DoSomething/rogue.git'

set :user, "dosomething"
set :group, "dosomething"
set :use_sudo, false

set :branch, "rogue-deploy"
if ENV['branch']
    set :branch, ENV['branch'] || 'rogue-deploy'
end

set :keep_releases, 1

set :linked_files, %w{.env}
set :linked_dirs, %w{images storage/logs storage/dumps storage/system}

namespace :deploy do

  task :artisan_migrate do
    run "cd #{release_path} && php artisan migrate --force"
  end

  task :artisan_cache_clear do
    run "cd #{release_path} && php artisan cache:clear"
  end

  task :react_render do
    run "forever stopall && forever start #{release_path}/bootstrap/react_server.js"
  end

  before :finishing, :artisan_migrate
  after :artisan_migrate, :react_render, :artisan_cache_clear

end
