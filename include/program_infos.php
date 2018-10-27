<div class="col-md-1 program-logo">
    <a href="<?php echo $db->getUrl(); ?>" title="<?php echo $db->getName(); ?>" target="_blank"><img src="<?php echo $db->getProfilePicture(); ?>"  id="program-profile-picture" /></a>
</div>
<div class="col-md-2">
    <a href="<?php echo $db->getUrl(); ?>" title="<?php echo $db->getName(); ?>" target="_blank"><h3><?php echo $db->getName(); ?></h3></a>
</div>
<div class="col-md-2">
    Report count: <b><?php echo $db->getTotalReport(); ?></b> <br />
    First report: <b><?php echo date('Y/m/d',$db->getFirstReportDate()); ?></b> <br />
    Last report: <b><?php echo date('Y/m/d',$db->getLastReportDate()); ?></b> <br />
</div>
<div class="col-md-2">
    Lowest bounty: <b><?php echo $db->getSmallestBounty(); ?>$</b> <br />
    Highest bounty: <b><?php echo $db->getHigherBounty(); ?>$</b> <br />
    Average bounty: <b><?php echo $db->getAverageBounty(); ?>$</b> <br />
</div>
<div class="col-md-2">
    <b>n - 1</b><br />
    <?php
        $n1 = $db->getReportsByMonth( date('m/Y',mktime(0,0,0,date('m')-1,date('d'),date('Y'))) );
        $n0 = $db->getReportsByMonth( date('m/Y') );
        $nd = $n0 - $n1;
        if( $nd < 0 ) {
            $c = 'p_positive';
            $n = $nd;
        } elseif( $nd > 0 ) {
            $c = 'p_negative';
            $n = '+'.$nd;
        } else {
            $c = 'p_null';
            $n = '-';
        }
    ?>
    Reports: <span class="<?php echo $c; ?>"><?php echo $n; ?></span><br />
    <?php
        $n1 = $db->getBountiesByMonth( date('m/Y',mktime(0,0,0,date('m')-1,date('d'),date('Y'))) );
        $n0 = $db->getBountiesByMonth( date('m/Y') );
        $nd = $n0 - $n1;
        if( $nd < 0 ) {
            $c = 'p_positive';
            $n = $nd.'$';
        } elseif( $nd > 0 ) {
            $c = 'p_negative';
            $n = '+'.$nd.'$';
        } else {
            $c = 'p_null';
            $n = '-';
        }
    ?>
    Bounties: <span class="<?php echo $c; ?>"><?php echo $n; ?></span><br />
</div>
