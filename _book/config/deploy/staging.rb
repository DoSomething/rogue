role :app, %w{dosomething@rogue-qa}

server 'rogue-qa', user: 'dosomething', roles: %w{app}, master: true

