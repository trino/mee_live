
<h3 class="page-title">
			To Do <small>(Reminders)</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="<?php echo $this->request->webroot;?>schedules/calender">Todo</a>
                        <i class="fa fa-angle-right"></i>
					</li>
                    <li>
                        <a href="">Schedule (<?php echo $this->request['pass'][0];?>)</a>
                    </li>
				</ul>
                

			</div>
            <div class="row">
                                    <div class="col-md-12">
											<div class="scroller" style="max-height: 600px;" data-always-visible="0" data-rail-visible="0" data-handle-color="#dae3e7">
												<div class="todo-tasklist">
                                                <?php 
                                                    if(count($events)>0){
                                                    foreach($events as $event){?>
													<a href="<?php echo $this->request->webroot;?>schedules/view/<?php echo $event->id;?>">
                                                    <div class="todo-tasklist-item todo-tasklist-item-border-green">
														
														<div class="todo-tasklist-item-title">
															 <?php echo $event->title;?>
														</div>
														<div class="todo-tasklist-item-text">
															<?php echo $event->description;?>
														</div>
														<div class="todo-tasklist-controls pull-left">
															<span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d M Y',strtotime($this->request['pass'][0]));?> </span>
															<!--<span class="todo-tasklist-badge badge badge-roundless">Urgent</span>-->
														</div>
													</div></a>
                                                <?php }
                                                }
                                                else
                                                    echo "No tasks for today.";
                                                ?>
                                                    <BR><a href="<?=$this->request->webroot;?>schedules/add?date=<?= $this->request['pass'][0]; ?>" id="event_add" type="button" class="btn red">Add Task </a>

                                                    <!--<a href="<?php echo $this->request->webroot;?>todo/view/1">
													<div class="todo-tasklist-item todo-tasklist-item-border-red">
														<img class="todo-userpic pull-left" src="<?php echo $this->request->webroot;?>img/uploads/male.png" width="27px" height="27px">
														<div class="todo-tasklist-item-title">
															 Homepage Alignments to adjust
														</div>
														<div class="todo-tasklist-item-text">
															 Lorem ipsum dolor sit amet, consectetuer dolore dolor sit amet.
														</div>
														<div class="todo-tasklist-controls pull-left">
															<span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d M Y',strtotime($this->request['pass'][0]));?> </span>
															<span class="todo-tasklist-badge badge badge-roundless">Important</span>
														</div>
													</div></a>
                                                    <a href="<?php echo $this->request->webroot;?>todo/view/1">
													<div class="todo-tasklist-item todo-tasklist-item-border-green">
														<img class="todo-userpic pull-left" src="<?php echo $this->request->webroot;?>img/uploads/male.png" width="27px" height="27px">
														<div class="todo-tasklist-item-title">
															 Slider Redesign
														</div>
														<div class="todo-tasklist-controls pull-left">
															<span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d M Y',strtotime($this->request['pass'][0]));?> </span>
															<span class="todo-tasklist-badge badge badge-roundless">Important</span>&nbsp;
														</div>
													</div></a>
                                                    <a href="<?php echo $this->request->webroot;?>todo/view/1">
													<div class="todo-tasklist-item todo-tasklist-item-border-blue">
														<img class="todo-userpic pull-left" src="<?php echo $this->request->webroot;?>img/uploads/male.png" width="27px" height="27px">
														<div class="todo-tasklist-item-title">
															 Contact Us Map Location changes
														</div>
														<div class="todo-tasklist-item-text">
															 Lorem ipsum amet, consectetuer dolore dolor sit amet.
														</div>
														<div class="todo-tasklist-controls pull-left">
															<span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d M Y',strtotime($this->request['pass'][0]));?> </span>
															<span class="todo-tasklist-badge badge badge-roundless">Postponed</span>&nbsp; <span class="todo-tasklist-badge badge badge-roundless">Test</span>
														</div>
													</div></a>
                                                    <a href="<?php echo $this->request->webroot;?>todo/view/1">
													<div class="todo-tasklist-item todo-tasklist-item-border-purple">
														<img class="todo-userpic pull-left" src="<?php echo $this->request->webroot;?>img/uploads/male.png" width="27px" height="27px">
														<div class="todo-tasklist-item-title">
															 Projects list new Forms
														</div>
														<div class="todo-tasklist-item-text">
															 Lorem ipsum dolor sit amet, consectetuer dolore psum dolor sit.
														</div>
														<div class="todo-tasklist-controls pull-left">
															<span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d M Y',strtotime($this->request['pass'][0]));?> </span>
														</div>
													</div></a>
                                                    <a href="<?php echo $this->request->webroot;?>todo/view/1">
													<div class="todo-tasklist-item todo-tasklist-item-border-yellow">
														<img class="todo-userpic pull-left" src="<?php echo $this->request->webroot;?>img/uploads/male.png" width="27px" height="27px">
														<div class="todo-tasklist-item-title">
															 New Search Keywords
														</div>
														<div class="todo-tasklist-item-text">
															 Lorem ipsum dolor sit amet, consectetuer sit amet ipsum dolor, consectetuer ipsum consectetuer sit amet dolore.
														</div>
														<div class="todo-tasklist-controls pull-left">
															<span class="todo-tasklist-date"><i class="fa fa-calendar"></i> <?php echo date('d M Y',strtotime($this->request['pass'][0]));?> </span>
															<span class="todo-tasklist-badge badge badge-roundless">Postponed</span>&nbsp;
														</div>
													</div></a>-->
                                                    
                                                    
												</div>
											</div>
										</div>
                        </div>