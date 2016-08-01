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

namespace :deploy do
  folders = %w{logs dumps system}

  task :link_folders do
    run "ln -nfs #{shared_path}/.env #{release_path}/"
    run "ln -nfs #{shared_path}/images #{release_path}/public"

    folders.each do |folder|
      run "rm -rf #{release_path}/storage/#{folder}"
      run "ln -nfs #{shared_path}/#{folder} #{release_path}/storage/#{folder}"
    end
  end

  task :artisan_migrate do
    run "cd #{release_path} && php artisan migrate --force"
  end

  task :artisan_cache_clear do
    run "cd #{release_path} && php artisan cache:clear"
  end

  task :react_render do
    run "forever stopall && forever start #{release_path}/bootstrap/react_server.js"
  end

  after :update, :cleanup
  after :symlink, link_folders
  after :link_folders, :artisan_migrate
  after :artisan_migrate, :react_render, :artisan_cache_clear

end
