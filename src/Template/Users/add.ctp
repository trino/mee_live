<div class="row ">
				<div class="col-md-12">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet box yellow">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Add/Edit users
							</div>
							
						</div>
						<div class="portlet-body">
                        <form class="form-inline" role="form" action="" method="post">
							<h4>Name</h4>
							
								<div class="form-group">
									<label class="sr-only" for="fname">First name</label>
									<input type="text" class="form-control" name="fname" id="fname" placeholder="First name">
								</div>
								<div class="form-group">
									<label class="sr-only" for="lname">Last name</label>
									<input type="text" class="form-control" name="lname" id="lname" placeholder="Lastname">
								</div>
								
							
							<hr>
							<h4>Login Details</h4>
						
								<div class="form-group">
									<label class="sr-only" for="email">Email address</label>
									<div class="input-icon">
										<i class="fa fa-envelope"></i>
										<input type="email" class="form-control" name="email" id="email" placeholder="Enter email">
									</div>
								</div>
                                <div class="form-group">
									<label class="sr-only" for="username">Username</label>
									<div class="input-icon">
										<i class="fa fa-envelope"></i>
										<input type="text" class="form-control" name="username" id="username" placeholder="Username">
									</div>
								</div>
								<div class="form-group">
									<label class="sr-only" for="password">Password</label>
									<div class="input-icon">
										<i class="fa fa-user"></i>
										<input type="password" class="form-control" name="password" id="password" placeholder="Password">
									</div>
								</div>
                                <div class="form-group">
									<label class="sr-only" for="cpass">Confirm Password</label>
									<div class="input-icon">
										<i class="fa fa-user"></i>
										<input type="password" class="form-control" id="cpass" placeholder="Confirm Password">
									</div>
								</div>
								
							<hr>
							
							<h4>Contact detail</h4>
							
								<div class="form-group col-md-12">
									<label for="phone" class="col-md-2 control-label nopad">Phone</label>
									<div class="col-md-10 nopad">
										<div class="input-icon">
											<i class="fa fa-envelope"></i>
											<input type="text" name="phone" class="form-control" id="phone" placeholder="Phone">
										</div>
									</div>
								</div>
                                <p>&nbsp;</p>
								<div class="form-group col-md-12">
									<label for="address" class="col-md-2 control-label nopad">Address</label>
									<div class="col-md-10 nopad">
										<div class="input-icon right">
											
											<textarea  class="form-control" name="address" id="address" placeholder="Address"></textarea>
										</div>
										
									</div>
								</div>
								<div class="clearfix"></div>
							
							<hr>
                            <h4>Add Image</h4>
                            
                            <div class="form-group">
                                <img style="width: 60px; height:60px;" src="<?php echo $this->request->webroot;?>img/profile/male.png">
                                <input type="radio" value="male.png" name="img_gender">
                            </div>
                            <div class="form-group">
                                <img style="width: 60px; height:60px;" src="<?php echo $this->request->webroot;?>img/profile/female.png">
                                <input type="radio" value="female.png" name="img_gender"> &nbsp; &nbsp;
                            </div>
                            
                            <div class="form-group">
									<label class="sr-only" for="exampleInputEmail22">Add Image</label>
									<div class="input-icon">
										
										<a href="javascript:void(0)" class="btn btn-success"><i class="fa fa-envelope"></i> &nbsp; Add profile image</a>
									</div>
							</div>
                            
                            <hr>
                            
                            <h4>Document permission</h4>
                            
                            <div class="form-group">
                                <div><strong>Can view:</strong></div>
                                <span>     Contracts </span>
                                <input type="checkbox" name="canView_contracts">
                                <span>      Evidence </span>
                                <input type="checkbox" onclick="loadmore('evidence',$(this));" name="canView_evidence">
                                <span>      Templates </span>
                                <input type="checkbox" name="canView_templates">
                                <span>      Report </span>
                                <input type="checkbox" name="canView_client_memo" onclick="loadmore('report',$(this));">
                                <span>      Site Orders </span>
                                <input type="checkbox" name="canView_siteOrder" onclick="loadmore('siteorder',$(this));">
                                <span>      Training </span>
                                <input type="checkbox" name="canView_training">
                                <span>      Employee </span>
                                <input type="checkbox" name="canView_employee" onclick="loadmore('employee',$(this));">
                                <span>      KPI Audits </span>
                                <input type="checkbox" name="canView_KPIAudits">
                                <span>      Orders </span>
                                <input type="checkbox" name="canView_orders">
                                <span>      Deployment </span>
                                <input type="checkbox" name="canView_deployment_rate" onchange="if($(this).is(':checked')){$('.deploy_more').show();}else{$('.deploy_more').hide();if($('.is_client').is(':checked')){$('.is_client').click();}}">
                            </div>
                            <p>&nbsp;</p>
                            <div class="form-group">
                                <div><strong>Can view:</strong></div>
                                <span>     Contracts </span>
                                <input type="checkbox" name="canUpload_contracts">
                                <span>      Evidence </span>
                                <input type="checkbox" name="canUpload_evidence" onclick="loadupload('evidence',$(this));">
                                <span>      Templates </span>
                                <input type="checkbox" name="canUpload_templates">
                                <span>      Report </span>
                                <input type="checkbox" name="canUpload_client_memo" onclick="loadupload('report',$(this));">
                                <span>      Site Orders </span>
                                <input type="checkbox" name="canUpload_siteOrder" onclick="loadupload('siteorder',$(this));">
                                <span>      Training </span>
                                <input type="checkbox" name="canUpload_training">
                                <span>      Employee </span>
                                <input type="checkbox" name="canUpload_employee" onclick="loadupload('employee',$(this));">
                                <span>      KPI Audits </span>
                                <input type="checkbox" name="canUpload_KPIAudits">
                                <span>      Orders </span>
                                <input type="checkbox" name="canUpload_orders">
                                <span>      <?php echo ucfirst($settings->client); ?> Feedback </span>
                                <input type="checkbox" name="canUpload_client_memo1">
                                <span>      Deployment </span>
                                <input type="checkbox" name="canUpload_deployment_rate">
                            </div>
                            
                            
                            
                            <hr />
                            <h4>Notification settings</h4>                            
                            <div class="form-group">
                            <div>Can send message: &nbsp; <input type="checkbox" checked="checked" name="canEmail"></div>
                            </div>
                            <p>&nbsp;</p>
                            <div class="form-group">
                            <div>Can edit notification: &nbsp; <input type="checkbox" checked="checked" name="canEdit"></div>
                            </div>
                            <p>&nbsp;</p>
                            <div class="form-group">
                            <div>Receive email when someone sends me message: &nbsp; <input type="checkbox" checked="checked" name="receive1"></div>
                            </div>
                            <p>&nbsp;</p>
                            <div class="form-group">
                            <div>Receive email when <?php echo strtolower($settings->document); ?> is uploaded : &nbsp; <input type="checkbox" checked="checked" name="receive2"></div>
                            
                            <div style="display: none;">
                            <p>&nbsp;</p>
                            <span>     Contracts </span>
                            <input type="checkbox" checked="checked" name="Email_contracts">
                            <span>     Evidence </span>
                            <input type="checkbox" checked="checked" name="Email_evidence">
                            <span>     Templates </span>
                            <input type="checkbox" checked="checked" name="Email_templates">
                            <span>     Report </span>
                            <input type="checkbox" checked="checked" name="Email_client_memo">
                            <span>      Site Orders </span>
                            <input type="checkbox" checked="checked" name="Email_siteOrder">
                            <span>      Training </span>
                            <input type="checkbox" checked="checked" name="Email_training">
                            <span>      Employee </span>
                            <input type="checkbox" checked="checked" name="Email_employee">
                            <span>      KPI Audits </span>
                            <input type="checkbox" name="Email_KPIAudits">
                            <span>      Orders </span>
                            <input type="checkbox" name="Email_orders">
                            <span>      Deployment </span>
                            <input type="checkbox" name="Email_deployment">
                            </div>
                            </div>
                            <hr />
                            <h4>Assign jobs to user</h4>
                            <div class="form-group">
                            <span>     Abc </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>     Cde </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>     Fgh </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>     Ijk </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>      Lmn </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>      Opq </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>      Rst </span>
                            <input type="checkbox" checked="checked" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>      Uvw </span>
                            <input type="checkbox" name=""> &nbsp; &nbsp; &nbsp; &nbsp;
                            <span>      Xyz </span>
                            <input type="checkbox" name="">
                            
                            </div>
                            <hr />
							<h4>Admin priviledge</h4>
							<div class="form-group">
									<label class="sr-only" for="admin">Make admin</label>
									<div class="input-icon">
										
										<input type="checkbox" name="admin" value="1" id="admin" class="form-control"/> Make Admin
									</div>
							</div>
                            <hr />
                            <div class="form-group">
									
									
										
										<input type="submit" value="Submit" class="form-control btn btn-success"/>
									
							</div>
                            </form>
						</div>
					</div>
					<!-- END SAMPLE FORM PORTLET-->
				</div>
			</div>
