<div style="display:none;" class="modal fade" id="modalReportEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<form action="ajax.php" id="formReportEdit" class="form-horizontal">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
	                <h4 class="modal-title" id="myModalLabel">Edit this report</h4>
	            </div>
	            <div class="modal-body">
					<div class="row">
							<div class="col-md-12">
								<input type="hidden" name="_a" value="report-edit" />
								<input type="hidden" name="key" value="" />
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="title">Id</label>
	                                <div class="col-sm-9">
	                                    <input type="text" name="id" required="required" class="form-control" placeholder="id..." value="">
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="title">Platform</label>
	                                <div class="col-sm-9">
	                                    <input type="text" name="platform" required="required" class="form-control" value="external" placeholder="platform...">
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="title">Rating</label>
	                                <div class="col-sm-9">
				                        <div class="radio radio-5 radio-inline col-md-1">
				                            <input type="radio" name="rating" required="required" value="5">
				                            <label class="required">P5</label>
				                        </div>
				                        <div class="radio radio-4 radio-inline col-md-1">
				                            <input type="radio" name="rating" required="required" value="4">
				                            <label class="required">P4</label>
				                        </div>
				                        <div class="radio radio-3 radio-inline col-md-1">
				                            <input type="radio" name="rating" required="required" value="3">
				                            <label class="required">P3</label>
				                        </div>
				                        <div class="radio radio-2 radio-inline col-md-1">
				                            <input type="radio" name="rating" required="required" value="2">
				                            <label class="required">P2</label>
				                        </div>
				                        <div class="radio radio-1 radio-inline col-md-1">
				                            <input type="radio" name="rating" required="required" value="1">
				                            <label class="required">P1</label>
				                        </div>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="state">Status</label>
	                                <div class="col-sm-9">
	                                    <select name="state" required="required" class="form-control">
	                                    	<?php foreach( Report::T_STATE as $state ) { ?>
	                                    		<option value="<?php echo $state; ?>"><?php echo $state; ?></option>
	                                    	<?php } ?>
	                                    </select>
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="title">Title</label>
	                                <div class="col-sm-9">
	                                    <input type="text" name="title" required="required" class="form-control" placeholder="title..." value="">
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="title">Tags</label>
	                                <div class="col-sm-9">
	                                    <input type="text" name="tags" class="form-control" placeholder="tag1,tag2,tag3,..." value="">
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="program">Program</label>
	                                <div class="col-sm-9">
	                                    <input type="text" name="program" required="required" class="form-control" placeholder="program..." value="">
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group">
	                                <label class="col-sm-3 control-label required" for="bounty">Bounty</label>
	                                <div class="col-sm-9">
										<div class="input-group mb-2 mr-sm-2 mb-sm-0">
											<div class="input-group-addon">$</div>
											<input type="text" name="bounty" required="required" class="form-control" placeholder="1000">
	                                	</div>
	                                </div>
	                            </div>
							</div>
							<div class="col-md-12">
								<div class="form-group" style="margin-bottom:0px;">
	                                <label class="col-sm-3 control-label required" for="created_at">Created at</label>
	                                <div class="col-sm-9">
	                                    <input type="text" name="created_at" required="required" class="form-control" placeholder="yyyy/mm/dd" value="">
	                                </div>
	                            </div>
							</div>
					</div>
				</div>
	            <div class="modal-footer">
					<div class="row">
						<div class="col-md-4 text-left">
			                <input type="submit" class="btn btn-primary" id="confirm-save" value="Save">
						</div>
						<div class="col-md-4 text-center">
			                <input type="button" class="btn btn-warning" id="confirm-ignore" value="Ignore">
			                <input type="button" class="btn btn-warning" id="confirm-unignore" value="Unignore">
						</div>
						<div class="col-md-4">
			                <button type="button" class="btn btn-danger" id="confirm-delete">Delete</button>
						</div>
					</div>
	            </div>
			</form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.report-edit').on('click', function(e) {
            e.preventDefault();
            $('#modalReportEdit').modal();
        
	        var form = $('#formReportEdit');
            form[0].reset();
            
            var tr = $(this).parents('tr');
            var report_key = tr.attr( 'data-key' );
            
            $.post( 'ajax.php', {'_a':'report-get','key':report_key}, function(data) {
		        var report = jQuery.parseJSON( data );

	            var input_ignore = form.find('input[id="confirm-ignore"]');
	            var input_unignore = form.find('input[id="confirm-unignore"]');
	            if( report.ignore ) {
	            	input_ignore.hide();
	            	input_unignore.show();
	            } else {
	            	input_ignore.show();
	            	input_unignore.hide();
	            }
	            
	            if( report.rating ) {
		            var input_rating = form.find('input[name="rating"][value="'+report.rating+'"]');
		            input_rating.prop( 'checked', 'checked' );
	            }
	            
   	            var input_key = form.find('input[name="key"]');
	            input_key.val( report_key );

	            var input_state = form.find('select[name="state"]');
	            input_state.val( report.state );

	            var input_title = form.find('input[name="title"]');
	            input_title.val( report.title );

	            var input_tags = form.find('input[name="tags"]');
	            if( report.str_tags != '' ) {
		            input_tags.val( report.str_tags+', ' );	
	            }
	            
	            var input_program = form.find('input[name="program"]');
	            input_program.val( report.program );

	            var input_bounty = form.find('input[name="bounty"]');
	            input_bounty.val( report.total_bounty );

	            var input_created_at = form.find('input[name="created_at"]');
	            input_created_at.val( report.created_at );
	            
	            var input_id = form.find('input[name="id"]');
	            input_id.val( report.id );

	            var input_platform = form.find('input[name="platform"]');
	            input_platform.val( report.platform );
	            
	            if( report.manual ) {
	            	input_id.removeAttr( 'disabled' );
	            	input_platform.removeAttr( 'disabled' );
	            	input_state.removeAttr( 'disabled' );
	            	input_title.removeAttr( 'disabled' );
	            	input_program.removeAttr( 'disabled' );
	            	input_bounty.removeAttr( 'disabled' );
	            	input_created_at.removeAttr( 'disabled' );
	            	//input_id.parents('.col-md-12').show();
	            	//input_platform.parents('.col-md-12').show();
	            } else {
	            	input_id.attr( 'disabled', 'disabled' );
	            	input_platform.attr( 'disabled', 'disabled' );
	            	input_state.attr( 'disabled', 'disabled' );
	            	input_title.attr( 'disabled', 'disabled' );
	            	input_program.attr( 'disabled', 'disabled' );
	            	input_bounty.attr( 'disabled', 'disabled' );
	            	input_created_at.attr( 'disabled', 'disabled' );
	            	//input_id.parents('.col-md-12').hide();
	            	//input_platform.parents('.col-md-12').hide();
	            }
	            
	            window.setTimeout( function(){input_tags.focus();}, 500 );
            });
        });
        
        $('#modalReportEdit').on('click','#confirm-save',function(e){
            e.preventDefault();
            var form = $('#formReportEdit');
            var input_key = form.find('input[name="key"]');
            $.post( form.attr('action'), form.serialize(), function(data) {
            	key = input_key.val();
	            form[0].reset();
                $('#modalReportEdit').find('.close').click();
                //location.reload();
                reloadReportLine( key );
            });
        });
        
        $('#modalReportEdit').on('click','#confirm-unignore',function(e){
            e.preventDefault();
            var form = $('#formReportEdit');
            var input_key = form.find('input[name="key"]');
            $.post( 'ajax.php', {'_a':'report-unignore','key':input_key.val()}, function(data) {
	            form[0].reset();
                $('#modalReportEdit').find('.close').click();
                location.reload();
            });
        });
        
        $('#modalReportEdit').on('click','#confirm-ignore',function(e){
            e.preventDefault();
            var form = $('#formReportEdit');
            var input_key = form.find('input[name="key"]');
            $.post( 'ajax.php', {'_a':'report-ignore','key':input_key.val()}, function(data) {
	            form[0].reset();
                $('#modalReportEdit').find('.close').click();
                location.reload();
            });
        });
        
        $('#modalReportEdit').on('click','#confirm-delete',function(e){
            e.preventDefault();
            var form = $('#formReportEdit');
            var input_key = form.find('input[name="key"]');
            $.post( 'ajax.php', {'_a':'report-delete','key':input_key.val()}, function(data) {
	            form[0].reset();
                $('#modalReportEdit').find('.close').click();
                location.reload();
            });
        });
    });
</script>
