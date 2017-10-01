<div style="display:none;" class="modal fade" id="modalTagAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Add one tag or several</h4>
            </div>
            <div class="modal-body">
				<div class="row">
					<form action="ajax.php" id="formTagAdd" class="form-horizontal">
						<div class="col-md-12">
							<input type="hidden" name="_a" value="tag-add" />
							<input type="hidden" name="key" value="" />
							<input type="hidden" name="id" value="" />
						</div>
						<div class="col-md-12">
							<div class="form-group">
                                <label class="col-sm-3 control-label required" for="tag">Tag</label>
                                <div class="col-sm-9">
                                    <input type="text" name="tag" required="required" class="form-control" placeholder="tag1,tag2,tag3..." value="">
                                </div>
                            </div>
						</div>
					</form>
				</div>
			</div>
            <div class="modal-footer">
				<div class="row">
					<div class="col-md-6 text-left">
		                <button type="button" class="btn btn-primary" id="confirm-save">Save</button>
					</div>
				</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.tag-add').on('click', function(e) {
            e.preventDefault();
            $('#modalTagAdd').modal();
            
            var tr = $(this).parents('tr');
            var form = $('#formTagAdd');
            form[0].reset();
            
            var input_key = form.find('input[name="key"]');
            var input_id = form.find('input[name="id"]');
            var input_tag = form.find('input[name="tag"]');

            input_key.val( tr.find('.report-key').val().trim() );
            input_id.val( tr.find('.report-id').text().trim() );
            
            window.setTimeout( function(){input_tag.focus();}, 500 );
        });
        
        $('#modalTagAdd').on('click','#confirm-save',function(e){
            e.preventDefault();
            var form = $('#formTagAdd');
            $.post( form.attr('action'), form.serialize(), function(data) {
	            form[0].reset();
                $('#modalTadAdd').find('.close').click();
                location.reload();
            });
        });
    });
</script>
