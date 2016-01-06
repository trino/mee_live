
<h3 class="page-title">
			Calendar
			</h3>
			<div class="page-bar">
				<ul class="page-breadcrumb">
					<li>
						<i class="fa fa-home"></i>
						<a href="index.html">Home</a>
						<i class="fa fa-angle-right"></i>
					</li>
				
					<li>
						<a href="#">Calendar</a>
					</li>
				</ul>
                <a href="javascript:window.print();" class="floatright btn btn-info">Print</a>
			</div>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box green-meadow calendar">
						<div class="portlet-title">
							<div class="caption">
								<i class="fa fa-gift"></i>Schedule
							</div>
						</div>
						<div class="portlet-body">
							<div class="row">
								<div class="col-md-3 col-sm-12">
									<!-- BEGIN DRAGGABLE EVENTS PORTLET-->
									<!--<h3 class="event-form-title">Draggable Events</h3>-->
									<!--<div id="external-events">
										<form class="inline-form">
											<input type="text" value="" class="form-control" placeholder="Event Title..." id="event_title"/><br/>
											<a href="javascript:;" id="event_add" class="btn default">
											Add Event </a>
										</form>
										<hr/>
										<div id="event_box">
										</div>
										<label for="drop-remove">
										<input type="checkbox" id="drop-remove"/>remove after drop </label>
										<hr class="visible-xs"/>
									</div>-->
                                    <a href="<?php echo $this->request->webroot;?>schedules/add" id="event_add" type="button" class="btn red">
											Add Task </a>





                                            <div class="portlet box green-meadow main_desc" style="display: none; margin-top:20px;">
                        						<div class="portlet-title">
                        							<div class="caption">
                        								Description
                        							</div>
                        						</div>
                                                
                        						<div class="portlet-body descprition">
                                                           
                                                                
                                                </div>
                                                 <a href="" class="btn btn-danger delUrl" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                                                </div>                  
									<!-- END DRAGGABLE EVENTS PORTLET-->
								</div>
								<div class="col-md-9 col-sm-12">
									<div id="calendar" class="has-toolbar">
									</div>
								</div>
							</div>
							<!-- END CALENDAR PORTLET-->
						</div>
					</div>
				</div>
			</div>
<script src="<?php echo $this->request->webroot;?>assets/global/plugins/moment.min.js"></script>
<script src="<?php echo $this->request->webroot;?>assets/global/plugins/fullcalendar/fullcalendar.min.js"></script>
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?php echo $this->request->webroot;?>assets/admin/pages/scripts/calendar.js"></script>
<script>
jQuery(document).ready(function() {       
  $('#calendar').fullCalendar({
    eventLimit: true,
    events: [
            <?php 
            foreach($events as $event){
                                              
            $dat = explode(" ",$event->date);
            $date = $dat[0];
        ?>
        {
            title: '<?php echo $event->title;?>',
            desc: '<?php echo $event->description;?>',
            delUrl: '<?php echo $this->request->webroot."schedules/delete/".$event->id;?>',
            start: '<?php echo str_replace(" ","T",$event->date);?>',
            url: '<?php echo $this->request->webroot;?>schedules/edit/<?php echo $event->id;?>'
        },
    <?php }?>
   
    
        // other events here
    ],
    eventClick: function(event) {
        if (event.url) {
            window.open(event.url);
            return false;
        }
    },
    eventMouseover: function(calEvent, jsEvent, view) {
            
        $('.main_desc').show();
        $('.descprition').html(calEvent.desc);
       
        $('.delUrl').attr('href',calEvent.delUrl);
        //alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
        //alert('View: ' + view.name);

        // change the border color just for fun
        $(this).css('border-color', 'red');

    },
    eventMouseout: function(calEvent, jsEvent, view) {
            
        //$('.main_desc').hide();
        

    },
    dayClick: function(date, jsEvent, view) {
            var d = date.format();
            window.location.href = "<?php echo $this->request->webroot;?>schedules/date/"+d;
            $(this).css('background-color', 'red');

    }
});

   //Calendar.init();
   
});
</script>