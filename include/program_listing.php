<div class="col-md-12">
	<table id="listing" class="table table-bordered table-striped datatable">
        <thead>
        <tr>
            <th>Title</th>
            <th>Hacker</th>
            <th>Bounty</th>
            <th>Tags</th>
            <th>CreatedAt</th>
        </tr>
        </thead>
        <tbody>
        	<?php $cm = 0;
        		if( $t_reports ) {
        			foreach( $t_reports as $key=>$r ) {
        				if( $r->getIgnore() ) { $tr_class='ignored'; } else { $tr_class=''; }
        				?>
                        <tr class="<?php echo $tr_class; ?>" data-key="<?php echo $key; ?>">
                            <td class="report-state state_<?php echo $r->getState(); ?>">
                            	<span class="report-sstate">s:<?php echo $r->getState(); ?></span>
                            	<span class="report-rrating">r:<?php echo $r->getSeverity(); ?></span>
                            	<a href="<?php echo $r->getLink(); ?>" target="_blank" class="report-edit rating_<?php echo $r->getSeverity(); ?>"><span class="report-title"><?php echo $r->getTitle(); ?></span></a>
                            </td>
                            <td>
                            	<span class="report-program search-term"><?php echo ucwords($r->getReporter()); ?></span>
                            </td>
                            <td class="text-right">
                            	<span class="report-bounty"><?php echo $r->getTotalBounty(); ?></span> $
                            </td>
                            <td class="report-tags">
                            	<?php if( count($r->getTags()) ) {
                            			foreach( $r->getTags() as $t ) { ?>
                            		<span class="report-tag search-term"><?php echo $t; ?></span>
                            	<?php } 
                            	} else { ?>
                            		<span class="report-untaged">untaged</span>
                            	<?php } ?>
                            </td>
                            <td>
                            	<span class="report-created_at"><?php echo date('Y/m/d',$r->getCreatedAt()); ?></span>
                            </td>
                        </tr>
                <?php }
            	} ?>
        </tbody>
    </table>
</div>