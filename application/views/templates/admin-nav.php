			<nav class="navbar navbar-inverse navbar-fixed-top">
			  <div class="navbar-inner">
				<div class="container">
					<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</a>
					<a href="<?php echo base_url() ?>index.php/status" class="brand">Status</a>
					<div class="nav-collapse collapse">
						<?php if ($this->session->userdata('logged_in') == 'yes'){ ?>
							<ul class="nav pull-right login">
								<li><a href="<?php echo site_url('users/user/'.$this->session->userdata("id")); ?>" class="name">Welcome <?php echo $this->session->userdata('username'); ?>!</a> <a href="<?php echo base_url() ?>index.php/login/logout">Log out.</a></li>
							</ul>
						<?php } ?>
						<ul class="nav pull-left">
							<?php 
								$navigation = array (
									"Add Node" => "add/node",
									"Add Content Type" => "add/content_type",
									"Add View" => "add/view",
									"Add User" => "users/add",
									"Permissions" => "permissions",
								);
								$navPermissions = array (
									"Add Node" => "2:2",
									"Add Content Type" => "1:2",
									"Add View" => "0:2",
									"Add User" => "4:2",
									"Permissions" => "3:0, 3:1",
								);
								foreach ($navigation as $name => $url){
									$minPermission = $navPermissions[$name];
									if ($this->Permission->level($minPermission)){;
										$urlSegments = explode('/', $url);
										if (!isset($urlSegments[1])){
											$urlSegments[1] = "";
										} ?>
										<li <?php 
										if (($this->uri->segment(1) == $urlSegments[0])&&($this->uri->segment(2) == $urlSegments[1])){
											echo 'class="active"';
										} ?>>
											<a href="<?php echo site_url($url); ?>"><?php echo $name ?></a>
										</li>
									<?php
									}
								}
							?>
						</ul>
					</div>
				</div>
			  </div>
			</nav>