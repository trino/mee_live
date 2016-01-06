<?php $settings = $this->requestAction('settings/get_settings');?>
<h3 class="page-title">
			<?php echo ucfirst($settings->document);?> <small>View/Edit/Delete Drafts</small>
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="">Drafts</a>
					</li>
				</ul>
				<div class="page-toolbar">
					<div id="dashboard-report-range" class="pull-right tooltips btn btn-fit-height grey-salt" data-placement="top" data-original-title="Change dashboard date range">
						<i class="icon-calendar"></i>&nbsp;
						<span class="thin uppercase visible-lg-inline-block">&nbsp;</span>&nbsp;
						<i class="fa fa-angle-down"></i>
					</div>
				</div>
			</div>
<div class="row">
    <div class="col-md-4 col-sm-12">
                    <select class="form-control">
                        <option value="">Uploaded by</option>
                        <option value="0">Super Admin</option>
                        <option value="3">Admin</option>
                        <option value="4">Nick Smith</option>
                        <option value="5">James Blont</option>
                        <option value="6">Mark Henry</option>
                        <option value="7">John Lenon</option>
                        <option value="8">Elvis Moore</option>
                        <option value="9">Peter Brown</option>
                        <option value="10">Jimmy Green</option>
                        <option value="11">Robert Black</option>
                        
                    </select>
             </div>
             <div class="col-md-4 col-sm-12">
                    <select class="form-control">
                        <option value=""><?php echo ucfirst($settings->document);?> type</option>
                        <option value="0">Contract</option>
                        <option value="3">Evidence</option>
                        <option value="4">Report</option>
                        <option value="5">Site Order</option>
                        <option value="6">Orders</option>
                        <option value="7">Template</option>
                        <option value="8">KPI Audit</option>
                        <option value="9">Others</option>
                        
                    </select>
             </div>
             <div class="col-md-4 col-sm-12">
                    <select class="form-control">
                        <option value=""><?php echo ucfirst($settings->client);?></option>
                        <option value="0">Client 1</option>
                        <option value="3">Client 2</option>
                        <option value="4">Client 3</option>
                        <option value="5">Client</option>
                        
                    </select>
             </div>
             <div class="clearfix"></div>
             <hr />
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>
                    <?php echo ucfirst($settings->document);?>
                </div>
            </div>    
            <div class="portlet-body">
             
                <div class="table-responsive">
                    <table class="table table-condensed">
                    	<thead>
                    		<tr>
                    			<th><?php echo ucfirst($settings->document);?> type</th>
                                <th>Prepared for</th>
                    			<th><?php echo ucfirst($settings->client);?></th>
                    			<th>Uploaded by</th>
                    			<th>Uploaded on</th>
                    			<th>Files</th>
                                <th>Status</th>                    			
                    			<th class="actions"><?= __('Actions') ?></th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    	
                    		<tr>
                    			<td>Orders</td>
                                <td>Rob Anthony</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">
									<span class="label label-sm label-info">Draft</span>

								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => '#'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Orders</td>
                                <td>Jimmy Hendrix</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                   			    <td class="">
<span class="label label-sm label-info">Draft</span>

								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Orders</td>
                                <td>Angela Stuart</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">

									<span class="label label-sm label-info">Draft</span>


								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Orders</td>
                                <td>Jim Morrison</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">

									<span class="label label-sm label-info">Draft</span>


								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr><tr>
                    			<td>Orders</td>
                                <td>Jacob Brown</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">
									<span class="label label-sm label-info">Draft</span>

								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Orders</td>
                                <td>Peter Smith</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">

									<span class="label label-sm label-info">Draft</span>


								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Orders</td>
                                <td>Jude Brown</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">

									<span class="label label-sm label-info">Draft</span>


								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Orders</td>
                                <td>Luke Smith</td>
                    			<td>Client name 1</td>
                    			<td>Admin</td>
                    			<td>12-05-2014 03:20:00</td>
                    			<td><a href="#">DummyFile.docx</a></td>
                    			<td class="">

									<span class="label label-sm label-info">Draft</span>

								</td>
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => '#'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                    
                    	
                    	</tbody>
            	</table>
            	<div class="paginator">
                    <ul class="pagination">
                    <li class="prev disabled">
                    <a href="">< previous</a>
                    </li>
                    <li class="active">
                    <a href="#">1</a>
                    </li>
                    <li>
                    <a href="#">2</a>
                    </li>
                    <li class="next">
                    <a href="#" rel="next">next ></a>
                    </li>
                    </ul>
                </div>
                </div>
            </div>
        </div>
