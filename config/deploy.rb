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
