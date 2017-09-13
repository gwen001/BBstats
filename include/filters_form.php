<form id="formSearch" method="GET" class="form-horizontal">
	<div class="col-md-5">
		<div class="form-group">
            <label class="col-sm-3 control-label required" for="arus_project_add_name">Start date</label>
            <div class="col-sm-6">
                <input type="text" name="start_date" required="required" class="form-control" placeholder="dd/mm/yyyy" value="<?php echo $start_date; ?>">
                <span class="help-block m-b-none"></span>
            </div>
        </div>
	</div>
	<div class="col-md-5">
		<div class="form-group">
            <label class="col-sm-3 control-label required" for="arus_project_add_name">End date</label>
            <div class="col-sm-6">
                <input type="text" name="end-date" required="required" class="form-control" placeholder="dd/mm/yyyy" value="<?php echo $end_date; ?>">
                <span class="help-block m-b-none"></span>
            </div>
        </div>
	</div>
	<div class="col-md-2">
		<button class="btn btn-primary" type="submit">Go!</button>
	</div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
    	//$('#formSearch').find('input[name="start_date"]').focus();
    });
</script>
