<!DOCTYPE html>
	<html>
		<head>
			<title>CO2 Trends - OVG - Ecole du Cinquantenaire</title>
			<style text="text/css">
				@import url("bootstrap/css/bootstrap.min.css");
			</style>
		</head>
		
		<body>
			<div>			
				<nav class="navbar navbar-default" role="navigation">
					<div class="container">
						<div class="row">
							<div class="navbar-header">
								<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#titre-collapse">
									<span class="sr-only">Menus</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<a class="navbar-brand" href="#">SafeGoma data trends</a>
							</div>
							
							<div class="collapse navbar-collapse" id="titre-collapse">
								<ul class="nav navbar-nav navbar-right">
									<ul class="nav navbar-nav navbar-right">
										<li><a href="http://www.imconintl.com" class="dropdown-toggle" data-toggle="dropdown"> Go to Imcon website </a></li>
									</ul>
								</ul>
							</div>
						</div>
						
					</div>
				</nav>
		
		<div class="container">
			<div class="row">
			
				<div class="col-md-10">
					<style type="text/css">
						#zoneTrend{
							width:100%;
							height:560px;
						}
					</style>
					
					<canvas id="zoneTrend"> </canvas>
				</div>
				
				<div class="col-md-2">
					Display by: <br/><br/>
					<select class="form-control" id="intervalPeriod">
												               		  <option value="hour" selected="selected">Hour</option>
														              <option value="minute">Minute</option>
														              <option value="hour">Hour</option>
														              <option value="day">Day</option>
														              <option value="month">Month</option>
														              <option value="year">Year</option>
												               	</select><br/>
					
					<button class="form-control" id="ligneDisplay">Ligne</button><br/>
					<button class="form-control" id="histogrammeDisplay">Histogramme</button><br/>
					<button class="form-control" id="sectorDisplay">Sector</button><br/>
					
					<img style="width:130px;" src="img/34ff79_2e22d7c29d56476081692588623794d5.png_srz_856_209_85_22_0.50_1.20_0.00_.png"/><br/><br/>
					<img style="width:100px;" src="img/OVG-logo-NEW2light-300x300.jpg"/><br/><br/>
					<img  style="width:100px;" src="img/images.jpeg"/><br/><br/>
					<img style="width:100px; "	src="img/225px-Flag_of_the_Democratic_Republic_of_the_Congo.svg.png"/>
					
				</div>
			</div>
			<footer class="navbar navbar-fixed-bottom text-center">
				<a href="http://www.oneplaneteducation.com">One Education Network</a> | 
				<a href="http://www.imconintl.com">Imcon International</a>
			</footer>
		</div>
			<script type="text/javascript" src="jquery-2.1.1.min.js"></script>
			<script type="text/javascript" src="bootstrap/js/bootstrap.min.js"></script>
			<script type="text/javascript" src="Chart.js/Chart.min.js"></script>
			<script type="text/javascript" src="Chart.js/utils.js"></script>
			<script type="text/javascript" src="javascript.js"></script>
		</body>
	</html>