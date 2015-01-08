<header class="page">
	<a class="nav-button" href="{$siteurl}">Back</a>
	<h1>Example Site</h1>
</header>
<main>
  <p>Below is some example content to give an impression of how things work:</p>
	<div class="picture">
		<nav>
			<a href="{$siteurl}/{$lastitem}" class="entry-back"></a>
			<a href="{$siteurl}/{$nextitem}" class="entry-forward"></a>
		</nav>
	</div>
	<div class="player">
		<audio src="{$siteurl}/{$assetdir}/audio/{$file}.mp3" preload="auto" />
	</div>
	<article class="description">{$description}</article>
</main>
