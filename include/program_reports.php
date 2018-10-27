<h4>Reports you can read (<span class="report-cnt"></span>)</h4>
<table class="report-table">
    <tbody>
    <?php foreach( $program->hacktivity as $r ) {
        if( $r->title ) { ?>
            <tr>
                <td width="100"><?php echo date('Y/m/d',$r->getCreatedAt()); ?></td>
                <td><a href="<?php echo $r->getLink(); ?>" class="severity-<?php echo $r->getSeverity(); ?>" target="_blank"><?php echo $r->getTitle(); ?></a></td>
                <td align="right" width="100"><?php echo $r->getTotalBounty(); ?>$</td>
            </tr>
        <?php }
    } ?>
    </tbody>
</table>

<script type="text/javascript">
    $(document).ready(function(){
        $('.report-cnt').html( $('.report-table tr').length );
    });
</script>