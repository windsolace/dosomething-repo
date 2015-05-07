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
			<div id = "profile-info">
				<script id = "user-profile-tpl" type = "text/html">
				<div class="row">
					<div class = "grid-12 center">
						<span id = "profile-pic">
							<img class = "circle" src = "wp-content/themes/flat-boxy/img/profilepic1.jpg"/></span>
						<div>
							<span id = "profile-name">Guest</span><br/>
							<span id = "profile-age"></span>
						</div>
					</div>
				</div>
				<div class="row clear">
					<div class="grid-12 columns">
						<div class = "center" id = "upper-content">
							<ul class="no-list rate-list">
									<li><span class = "icon up-arrow"><span><%= data.userProfile.reviews[0].upvotes %></span></span> </li>
									<li><span class = "icon down-arrow"><span><%= data.userProfile.reviews[0].downvotes %></span></span> </li>
									<li><span class = "icon tick-mark"><span><%= data.userProfile.reviews[0].done %></span></span></li>
							</ul>
						</div>
					</div>
				</div>
				</script>
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
							
						</table>
						<script id = "past-activities-tpl" type = "text/html">
							<tbody>
								<% if(data.activities.length > 0) {%>

									<% _.each(data.activities, function(item){ %>
											<tr>
												<td><%= item.name %></td>
												<td>X days ago</td>
											</tr>
									<% }); %>
								<% } else { %>
									<tr>
										<td>No activities recorded.</td>
									</tr>
								<% } %>

							</tbody>
						</script>
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