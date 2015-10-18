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
							<img class = "circle" src = ""/></span>
						<div>
							<span id = "profile-name"><%= data.userProfile.displayName%></span><br/>
							
							<% 
								var accountAgeStr = "";
								if(data.userProfile){
									if(data.userProfile.accountAge.years) accountAgeStr+=data.userProfile.accountAge.years+" "; 
									if(data.userProfile.accountAge.months) accountAgeStr+=data.userProfile.accountAge.months+" "; 
									if(data.userProfile.accountAge.days) accountAgeStr+=data.userProfile.accountAge.days+" "; 
								}
							%>
							<span id = "profile-age"><%= accountAgeStr %></span>
						</div>
					</div>
				</div>
				<div class="row clear">
					<div class="grid-12 columns">
						<div class = "center" id = "upper-content">
							<% if(data.userProfile){ %>
								<ul class="no-list rate-list">
									<li><span class = "icon up-arrow"><span><%= data.userProfile.reviews[0].upvotes %></span></span> </li>
									<li><span class = "icon down-arrow"><span><%= data.userProfile.reviews[0].downvotes %></span></span> </li>
									<li><span class = "icon tick-mark"><span><%= data.userProfile.reviews[0].done %></span></span></li>
								</ul>
							<% } %>
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
												<td><a href = "<%= item.url %>"><%= item.name %></a></td>
												<% if(data.activityType == "done"){ %>
													<td><%= item.done_date %></td>
												<% } %>
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