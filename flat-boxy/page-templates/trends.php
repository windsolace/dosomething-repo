<?php
	/*
		* Template Name: Trends Listing
		*
		* @package WordPress
		* @subpackage Flat_Boxy
		* @since Flat_Boxy v1
	*/


get_header(); ?>
<?php the_breadcrumb(); ?>
<div id="body-wrapper">
	
	<?php
		if ( is_front_page() && twentyfourteen_has_featured_posts() ) {
			// Include the featured content template.
			get_template_part( 'featured-content' );
		}
	?>
	
	
	<!-- Start of navigation-->
	<?php include(__DIR__."/../sidenav.php");	?>
	<!-- End of navigation -->

	<section id = "main" class = "full">
		<div id = "main-content">

			<h2><?php echo get_the_title() ?></h2>

			<p>
				<?php
					//Start the Loop
					while(have_posts()) : the_post();
						//Include the content from editor
						the_content();
					endwhile
				?>
			</p>
			<div class = "cell-container">

				<p><form role="search" method="get" id="searchform" class="searchform" action="http://hydi.voqux.com/">
					<div>
						<label class="screen-reader-text" for="s">Search for:</label>
						<input type="text" value="" name="s" id="s" />
						<input type="submit" id="searchsubmit" value="Search" />
					</div>
				</form></p>
			
				<div id = "top-searches" class = "cell trend-cell">
					<script id = "top-searches-template" type = "text/html">
						<h3 class = "">Top Searches
							<span class = "subtitle"><%= data.list.country.name %></span>
						</h3>
						<ul class = "no-list">
							<% var count = 0; %>
							<% _.each(data.list.trendList, function(item){ %>
								<% if(count < 10) { %>
									<li><a href = "http://www.google.com/#q=<%= item.title %>&tbm=nws" target="_blank"><%= item.title %></a></li>
								<% count++} %>
							<% }); %>
						</ul>
					</script>
				</div>

				<div id = "twitter-trends" class = "cell trend-cell">
					<script id = "twitter-trends-template" type = "text/html">
						<h3 class = "">Trending on Twitter
							<span class = "subtitle"><%= data.list.location.name %></span>
						</h3>
						<ul class = "no-list">
							<% var count = 0; %>
							<% _.each(data.list.trendList, function(item){ %>
								<% if(count < 10) { %>
									<li><a href = "<%= item.url %>" target="_blank"><%= item.name %></a></li>
								<% count++} %>
							<% }); %>
						</ul>
					</script>
				</div>
				

				<div class = "cell trend-cell">
					<h3 class = "">Eat
						<span class = "subtitle">Singapore</span>
					</h3>
					<ol>
						<li>trend 1</li>
						<li>trend 2</li>
						<li>trend 3</li>
						<li>trend 4</li>
						<li>trend 5</li>
						<li>trend 6</li>
						<li>trend 7</li>
						<li>trend 8</li>
						<li>trend 9</li>
						<li>trend 10</li>
					</ol>
				</div>
				<div class="cell trend-cell">
					<h3 class = "">Explore
						<span class = "subtitle">Malaysia</span>
					</h3>
					<ul class = "no-list">
						<li>trend 1</li>
						<li>trend 2</li>
						<li>trend 3</li>
						<li>trend 4</li>
						<li>trend 5</li>
						<li>trend 6</li>
						<li>trend 7</li>
						<li>trend 8</li>
						<li>trend 9</li>
						<li>trend 10</li>
					</ul>
				</div>	
			</div> <!-- .cell-container -->

		</div><!-- #main-content -->		
	</section><!-- #main -->

	<?php get_footer();?>	
<script>
	$(document).ready(function(){
		var trendsResponse = <?php echo hydi_getTrends("SG") ?>;
		trendsFn(trendsResponse);
	});
</script>


