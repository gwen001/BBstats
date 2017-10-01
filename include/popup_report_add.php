<div style="display:none;" class="modal fade" id="modalReportAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<form action="ajax.php" id="formReportAdd" class="form-horizontal">
	            <div class="modal-header">
	                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
	                <h4 class="modal-title" id="myModalLabel">Add a report</h4>
	            </div>
	            <div class="modal-body">
					<div class="row">
							<div class="col-md-12">
								<input type="hidden" name="_a" value="report-add" />
								<input type="hidden" name="key" value="" />
								<input type="hidden" name="id" value="" />
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
	                                    <input type="text" name="tags" class="form-control" placeholder="tag1, tag2, tag3, ..." value="">
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
						<div class="col-md-6 text-left">
			                <input type="submit" class="btn btn-primary" id="confirm-save" value="Save">
						</div>
					</div>
	            </div>
			</form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#add-bounty-btn').on('click', function(e) {
            e.preventDefault();
            $('#modalReportAdd').modal();
        
	        var form = $('#formReportAdd');
            form[0].reset();
            var input_id = form.find('input[name="id"]');
            
            window.setTimeout( function(){input_id.focus();}, 500 );
        });
        
        $('#modalReportAdd').on('click','#confirm-save',function(e){
            e.preventDefault();
            var form = $('#formReportAdd');
            $.post( form.attr('action'), form.serialize(), function(data) {
	            form[0].reset();
                $('#modalReportAdd').find('.close').click();
                location.reload();
            });
        });
    });
</script>
