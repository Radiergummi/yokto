# yokto
minimalist website framework designed for the most basic web apps and sites.

####Current status
It's alive! It's alive! The current build runs perfectly well.
You can see a running instance of yokto here: http://yokto.9dev.de

####What yokto *is*
- quick
- simple
- extendable



####What yokto *not is*
- ready for the technically unsavvy
- equipped with many advanced features
- complete


###How yokto works
yokto is completely route based. That means, all requests get passed to yoktos router.php which checks the URI against all files in the content directory, aswell as a set of statically defined routes. Now we know what the client requests and can deliver content stored for a given route.
