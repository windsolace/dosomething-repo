<?php 
	/*
		* Template Name: User Profile Template
		*
		* @package WordPress
		* @subpackage Flat_Boxy
		* @since Flat_Boxy v1
	*/
get_header(); ?>
<?php the_breadcrumb(); ?>

	<section id = "main">
		<div id = "main-content">
			<div class="row">
				<div class = "grid-12 center">
					<span id = "profile-pic">
						<img class = "circle" src = "wp-content/themes/flat-boxy/img/profilepic1.jpg"/></span>
					<div>
						<span id = "profile-name">username</span><br/>
						<span id = "profile-age">XX months</span>
					</div>
				</div>
			</div>
			<div class="row clear">
				<div class="grid-12 columns">
					<div class = "center" id = "upper-content">
						<ul class="no-list rate-list">
							<script id = "review-count-tpl" type = "text/html">
								<li><span class = "icon up-arrow"><span><%= data.userProfile.reviews[0].upvotes %></span></span> </li>
								<li><span class = "icon down-arrow"><span><%= data.userProfile.reviews[0].downvotes %></span></span> </li>
								<li><span class = "icon tick-mark"><span><%= data.userProfile.reviews[0].done %></span></span></li>
							</script>
						</ul>
					</div>
				</div>
			</div>
			<!-- List content for type of vote --> 
			<div class="row clear">
				<div class="grid-12 columns">
					<button id = "transit-up" class = "btn-transit light-gray" disabled style="display:none;">
						<span class="up"></span>^
					</button>
					<div style="display:none;" id = "past-list">
						<div><h2 class="tick-mark-single">LIKE</h2></div>
						<table id = "past-activities">
							<tbody>
								<tr>
									<td>The Minds Cafe</td>
									<td>2 days ago</td>
								</tr>
								<tr>
									<td>Marche</td>
									<td>5 days ago</td>
								</tr>
								<tr>
									<td>313 Somerset Cha Cafe @ The Basement</td>
									<td>10 days ago</td>
								</tr>
								<tr>
									<td>The Fat Boys</td>
									<td>10 days ago</td>
								</tr>
								<tr>
									<td>Astons Specialities @ Center Point</td>
									<td>10 days ago</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- End list content -->
			
		</div>
	</section>


<?php get_footer();?>	
<script>
	$(document).ready(function(){
		userProfileFn();
	});
</script>