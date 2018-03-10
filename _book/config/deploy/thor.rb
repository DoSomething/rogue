role :app, %w{dosomething@rogue-thor}

server 'rogue-thor', user: 'dosomething', roles: %w{app}, master: true

