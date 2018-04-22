<div class="col-md-12">
	<table id="listing" class="table table-bordered table-striped datatable">
        <thead>
        <tr>
            <th width="20"></th>
            <th>Title</th>
            <th>Program</th>
            <th>Bounty</th>
            <th>Tags</th>
            <th>CreatedAt</th>
        </tr>
        </thead>
        <tbody>
        	<?php $cm = 0;
        		if( $t_reports ) {
        			foreach( $t_reports as $key=>$r ) {
        				$m = date( 'n', $r->getCreatedAt() );
        				$reput = $r->getTotalReputation();
        				if( $m != $cm ) { $tr_class='new-month';$cm=$m; } else { $tr_class=''; }
        				?>
                        <tr class="<?php //echo $tr_class; ?>" data-key="<?php echo $key; ?>">
                            <td align="center" class="report-state state_<?php echo $r->getState(); ?>">
                            	<span class="report-platform"><?php echo $r->getPlatform(); ?></span>
                            	<?php $link=$r->getLink(); $p_icon='img/'.$r->getPlatform().'.png'; if( !is_file($p_icon) ) { $p_icon='img/unknown.png'; } ?>
                            	<span class="report-id">
                            		<?php if( $link ) { ?>
                            		<a href="<?php echo $r->getLink(); ?>" title="<?php echo $r->getPlatform().' - #'.$r->getId(); ?>" target="_blank">
	                            	<?php } ?>
                            		<img src="<?php echo $p_icon; ?>" width="16" alt="<?php echo $r->getPlatform().' - #'.$r->getId(); ?>" title="<?php echo $r->getPlatform().' - #'.$r->getId(); ?>">
                            		<?php if( $link ) { ?>
                            		</a>
	                            	<?php } ?>
                            	</span>
                            </td>
                            <td>
                            	<span class="report-sstate">s:<?php echo $r->getState(); ?></span>
                            	<span class="report-rrating">r:p<?php echo $r->getRating(); ?></span>
                            	<a href="javascript:;" class="report-edit rating_<?php echo $r->getRating(); ?>"><span class="report-title"><?php echo $r->getTitle(); ?></span></a>
                            	<?php  if( $reput > 0 ) { ?>
                            	<span class="report-reputation reputation-positive"><?php echo '+'.$reput; ?></span>
                            	<?php } elseif( $reput < 0 ) { ?>
                            	<span class="report-reputation reputation-negative"><?php echo $reput; ?></span>
                            	<?php } elseif( $reput === 0 ) { ?>
                            	<span class="report-reputation reputation-zero">0</span>
                            	<?php } ?> 
                            </td>
                            <td>
                            	<span class="report-program search-term"><?php echo ucwords($r->getProgram()); ?></span>
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