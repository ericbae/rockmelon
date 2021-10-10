@servers(['local' => 'localhost', 'web' => 'marvelogs'])m

@task('build', ['on' => 'local'])
  cd ~/Projects/rockmelon.app
  npm run production
@endtask

@task('commit', ['on' => 'local'])
  cd ~/Projects/rockmelon.app
  git add .
  git commit -m "latest"
  git push
@endtask

@task('prod-pull', ['on' => 'web'])
  cd /home/ericbae/rockmelon.app
  git pull origin master
@endtask

@task('prod-migrate', ['on' => 'web'])
  cd /home/ericbae/rockmelon.app
  php artisan migrate --force
@endtask

@task('dump-autoload', ['on' => 'web'])
  cd /home/ericbae/rockmelon.app
  composer dumpautoload
@endtask

@task('restart-supervisor', ['on' => 'web'])
  sudo service supervisor restart
@endtask

@story('deploy')
  build
  commit
  prod-pull
  dump-autoload
  restart-supervisor
@endstory

@story('deploy-migrate')
  build
  commit
  prod-pull
  prod-migrate
  dump-autoload
  restart-supervisor
@endstory