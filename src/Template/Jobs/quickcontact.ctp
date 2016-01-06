<h3 class="page-title">
			Quick Contacts <small>Add qucik contacts</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="#">Quick Contacts</a>
					</li>
				</ul>
				
			</div>
            <div class="row">
                <div class="col-md-12">
					<!-- BEGIN SAMPLE FORM PORTLET-->
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-user"></i> Add/Edit Quick Contact
							</div>
							
						</div>
                    <div class="portlet-body">
                        <form class="form-inline" role="form" action="" method="post">
                    <div class="form-group col-md-12">
							<label for="phone" class="col-md-2 control-label nopad">Contact Type</label>
							<div class="col-md-10 nopad">
								
									<select class="form-control">
                                        <option>Key contacts</option>
                                        <option>Staff contact</option>
                                        <option>Third Party contact</option>
                                    </select>
									
								
							</div>
						</div>
                        <div class="clearfix"></div>
                        <hr />
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Name</label>
							<div class="col-md-5 nopad">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Name">
								</div>
							</div>
						</div>
                        
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Title</label>
							<div class="col-md-5 nopad">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Title">
								</div>
							</div>
						</div>
                       <p>&nbsp;</p>
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Cell number</label>
							<div class="col-md-5 nopad">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Cell number">
								</div>
							</div>
						</div>
                       
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Cellular Carrier</label>
							<div class="col-md-5 nopad">
								<select name="key_cellular[]" class="form-control" >
                                    <option value="Rogers">Rogers</option>
                                    <option value="Bell">Bell</option>
                                    <option value="Fido">Fido</option>
                                    <option value="Telus Mobility">Telus Mobility</option>
                                    <option value="Koodo Mobile">Koodo Mobile</option>
                                    <option value="Wind Mobile">Wind Mobile</option>
                                    <option value="Lynx Mobility">Lynx Mobility</option>
                                    <option value="MTS Mobility">MTS Mobility</option>
                                    <option value="PC Telecom">PC Telecom</option>
                                    <option value="Aliant">Aliant</option>
                                    <option value="SaskTel">SaskTel</option>
                                    <option value="Virgin Mobile">Virgin Mobile</option>
                                </select>
							</div>
						</div>
                        <p>&nbsp;</p>
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Phone number</label>
							<div class="col-md-5 nopad">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Phone number">
								</div>
							</div>
						</div>
                        
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Email</label>
							<div class="col-md-5 nopad">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Email">
								</div>
							</div>
						</div>
                        <p>&nbsp;</p>
                        <div class="form-group col-md-6">
							<label for="phone" class="col-md-5 control-label nopad">Company</label>
							<div class="col-md-5 nopad">
								<div class="input-icon">
									<i class="fa fa-envelope"></i>
									<input type="text" name="phone" class="form-control" id="phone" placeholder="Company">
								</div>
							</div>
						</div>
                        <div class="clearfix"></div>
                    
                    <hr />
					<div class="form-group">
					   <input type="submit" value="Submit" class="form-control btn btn-success"/>
							
					</div>
                    
              </div>
             </div>
          </div>
       </div>      