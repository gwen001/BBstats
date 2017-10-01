<div style="display:none;" class="modal fade" id="modalMore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">More stats from <?php echo date('d/m/Y',$db->getFirstReportDate()); ?> to <?php echo date('d/m/Y'); ?></h4>
            </div>
            <div class="modal-body">
                <p>
                	You have submitted a total of <b><?php echo $db->getTotalReport(); ?></b> reports, earn <b><?php echo $db->getTotalBounty(); ?> $</b> and your reputation is now <b><?php echo $db->getTotalReputation(); ?></b>.
                </p>
                <p>
                <?php $diff = date_diff( new Datetime(), new Datetime(date('Y/m/d H:i:s',$db->getFirstReportDate())) ); $diff_m=($diff->y*12)+$diff->m+2; ?>
	                Your average report per month is: <b><?php printf( '%.02f', ($db->getTotalReport()/$diff_m) ); ?></b>.
	                <br />Your average bounty per month is: <b><?php printf( '%.02f', ($db->getTotalBounty()/$diff_m) ); ?> $</b>.
	                <br />Your average reputation per month is: <b><?php printf( '%.02f', ($db->getTotalReputation()/$diff_m) ); ?></b>.
                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#more-btn').on('click', function(e) {
            e.preventDefault();
            $('#modalMore').modal();
        });
    });
</script>
