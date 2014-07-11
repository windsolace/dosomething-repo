<?php 
/**
* Side navigation for the site
* Clicking on the menu icon will cause it to "expand"
**/
?>
<?php 
	$parent_title = get_parent_title($post);
?>
<script>
	$(document).ready(function(){
		//Hide button that matches current page parent's title
		var parentTitle = "<?php echo $parent_title ?>";;
		$('ul.nav-list').find('.menu-button').each(function(){
			var thisTitle = $(this).text();
			if(thisTitle === parentTitle){
				$(this).hide();
			}
		});
	});
</script>
<section id = "navigation" class = "closed">
	<div class = "nav-wrap">
		<a href="javascript:void(0)" alt = "Pull out menu">
		  <span id = "menu-icon"><img class = "menu" alt = "menu"/></span>
		</a>
		<ul class = "nav-list">
			
			<li>
				<a href = "<?php echo esc_url( home_url( '/' ) ); ?>"><div class= "menu-button">Home</div></a>
			</li>
			<li>
				<a href = "./eat"><div class= "menu-button eat">Eat</div></a>
			</li>
			<li>
				<a href = "./play"><div class= "menu-button play">Play</div></a>
			</li>
			<li>
				<a href = "./trend"><div class= "menu-button trend">Trend</div></a>
			</li>
			<li>
				<a href = "./explore"><div class= "menu-button explore">Explore</div></a>
			</li>
			<li>
				<a href = "./about"><div class= "menu-button">About</div></a>
			</li>
			<li>
				<a href = "./contact"><div class= "menu-button">Contact</div></a>
			</li>
			<li>
				<a href = "#"><span class= "menu-button login">Log In</span></a>
			</li>
		</ul>
	</div>
</section><!-- #navigation -->