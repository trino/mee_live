<link href="<?php echo $this->request->webroot;?>assets/global/plugins/jstree/dist/themes/default/style.min.css" rel="stylesheet" type="text/css"/>


<div class="row">
<div class="col-md-6">
    <div class="portlet blue-hoki box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Default Tree
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse">
                </a>
                <a href="#portlet-config" data-toggle="modal" class="config">
                </a>
                <a href="javascript:;" class="reload">
                </a>
                <a href="javascript:;" class="remove">
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div id="tree_1" class="tree-demo">
                <ul>
                    <li>
                        Root node 1
                        <ul>
                            <li data-jstree='{ "selected" : true }'>
                                <a href="#">
                                    Initially selected </a>
                            </li>
                            <li data-jstree='{ "icon" : "fa fa-briefcase icon-state-success " }'>
                                custom icon URL
                            </li>
                            <li data-jstree='{ "opened" : true }'>
                                initially open
                                <ul>
                                    <li data-jstree='{ "disabled" : true }'>
                                        Disabled Node
                                    </li>
                                    <li data-jstree='{ "type" : "file" }'>
                                        Another node
                                    </li>
                                </ul>
                            </li>
                            <li data-jstree='{ "icon" : "fa fa-warning icon-state-danger" }'>
                                Custom icon class (bootstrap)
                            </li>
                        </ul>
                    </li>
                    <li data-jstree='{ "type" : "file" }'>
                        <a href="http://www.jstree.com">
                            Clickanle link node </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="col-md-6">
    <div class="portlet green-meadow box">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa-cogs"></i>Checkable Tree
            </div>
            <div class="tools">
                <a href="javascript:;" class="collapse">
                </a>
                <a href="#portlet-config" data-toggle="modal" class="config">
                </a>
                <a href="javascript:;" class="reload">
                </a>
                <a href="javascript:;" class="remove">
                </a>
            </div>
        </div>
        <div class="portlet-body">
            <div id="tree_2" class="tree-demo">
            </div>
        </div>
    </div>
</div>
</div>

<script src="<?php echo $this->request->webroot;?>assets/global/plugins/jstree/dist/jstree.min.js"></script>
