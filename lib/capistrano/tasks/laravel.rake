namespace :custom do
  desc 'Run NPM run build'
  task :npm_run_build do
    on roles(:all) do
      within "#{release_path}" do
        execute "npm run build"
      end
    end
  end

  desc 'Run NPM run build'
  task :artisan_tasks do
    on roles(:all) do
      within "#{release_path}" do
        execute "php artisan migrate --force && php artisan cache:clear"
      end
    end
  end
end
