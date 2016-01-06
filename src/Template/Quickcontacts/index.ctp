<h3 class="page-title">
			Quick contacts 
			</h3>
    <div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="<?php echo $this->request->webroot;?>">Dashboard</a>
						<i class="fa fa-angle-right"></i>
					</li>
					<li>
						<a href="">Quick contacts</a>
					</li>
				</ul>
				
			</div>
<div class="row">
    <div class="col-md-12">
        <div class="portlet box blue">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i>
                    Quick contacts
                </div>
            </div>    
            <div class="portlet-body">
             <div class="col-md-6 col-sm-12 nopad">
                    <div id="sample_1_filter" class="dataTables_filter mar">
                        <form>
                            <label>                        
                            <input class="form-control input-inline" type="search" placeholder=" Search for Quick contacts" aria-controls="sample_1"> <button type="submit" class="btn btn-primary">Search</button>
                            </label>
                        </form>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed">
                    	<thead>
                    		<tr>
                    			<th><?= $this->Paginator->sort('Contact_type') ?></th>
                    			<th><?= $this->Paginator->sort('Name') ?></th>
                    			<th><?= $this->Paginator->sort('Title') ?></th>
                    			<th><?= $this->Paginator->sort('cell_number') ?></th>
                    			<th><?= $this->Paginator->sort('cellular_carrier') ?></th>
                    			<th><?= $this->Paginator->sort('Phone') ?></th> 
                                <th><?= $this->Paginator->sort('Email') ?></th> 
                                <th><?= $this->Paginator->sort('Company') ?></th>                    			
                    			<th class="actions"><?= __('Actions') ?></th>
                    		</tr>
                    	</thead>
                    	<tbody>
                    	
                    		<tr>
                    			<td>Test</td>
                    			<td>test</td>
                    			<td>test</td>
                    			<td>Rest</td>
                    			<td>Test</td>
                    			<td>Test</td>
                                <td>Test</td> 
                                <td>Test</td>                     			
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Test</td>
                    			<td>test</td>
                    			<td>test</td>
                    			<td>Rest</td>
                    			<td>Test</td>
                    			<td>Test</td>
                                <td>Test</td> 
                                <td>Test</td>                     			
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Test</td>
                    			<td>test</td>
                    			<td>test</td>
                    			<td>Rest</td>
                    			<td>Test</td>
                    			<td>Test</td>
                                <td>Test</td> 
                                <td>Test</td>                     			
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Test</td>
                    			<td>test</td>
                    			<td>test</td>
                    			<td>Rest</td>
                    			<td>Test</td>
                    			<td>Test</td>
                                <td>Test</td> 
                                <td>Test</td>                     			
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                            <tr>
                    			<td>Test</td>
                    			<td>test</td>
                    			<td>test</td>
                    			<td>Rest</td>
                    			<td>Test</td>
                    			<td>Test</td>
                                <td>Test</td> 
                                <td>Test</td>                     			
                    			<td class="actions">
                    				<?= $this->Html->link(__('View'), ['action' => 'view'], ['class' => 'btn btn-info']) ?>
                    				<?= $this->Html->link(__('Edit'), ['action' => 'edit'], ['class' => 'btn btn-primary']) ?>
                    				<?= $this->Form->postLink(__('Delete'), ['action' => 'delete'], ['class' => 'btn btn-danger'], ['confirm' => __('Are you sure you want to delete # {0}?')]) ?>
                    			</td>
                    		</tr>
                    
                    	
                    	</tbody>
            	</table>
            	<div class="paginator">
            		<ul class="pagination">
            			<?= $this->Paginator->prev('< ' . __('previous')); ?>
            			<?= $this->Paginator->numbers(); ?>
            			<?=	$this->Paginator->next(__('next') . ' >'); ?>
            		</ul>
            		
            	</div>
            </div>
