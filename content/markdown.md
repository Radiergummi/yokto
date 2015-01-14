<header class="this-is-html">
	<h1>Markdown Demo</h1>
</header>
<p>Below is some example content written with markdown to give an impression of how things work:</p>
<br/><br/>

#What is this?
##Where isthis?
###What time is it?
####Who am I?
#####Hello? Is anybody there?

This is an example markup file which will be parsed for the article with slug *foo-bar*.
You can use even github flavored markdown here, thanks to the fantastic [Parsedown](https://github.com/erusev/parsedown) library.
<br/><br/>
It works like so: The system looks for files using `PATH . 'content' . DS . $slug . '.md'`

Example: For `www.example.com/this-is-a-post` it looks for `<base>/content/this-is-a-post.md`  
<br/>
HTML works just as you would expect, in fact, the header above this content is just HTML in the .md file.
