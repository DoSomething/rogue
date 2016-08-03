role :app, %w{dosomething@rogue}

server 'rogue', user: 'dosomething', roles: %w{app}, master: true

