<div style="display:none;" class="modal fade" id="modalAbout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">About</h4>
            </div>
            <div class="modal-body">
                <b>Platform</b>
                <p>
                	For the moment only Hackerone is supported, but I'm waiting for your help to implement other platforms.
                	<br />Any ideas are welcome: <a href="https://github.com/gwen001/BBstats" target="_blank">https://github.com/gwen001/BBstats</a>
                </p>
                <b>Recommendation</b>
                <p>
                	It's not recommanded to manually change creation dates of reports and bounty amounts.
                	<br />Tags are a good way to set the type of a vulnerability: rce, xss, sqli, idor...
                </p>
                <b>Disclaimer</b>
                <p>
                	This program performs only read actions.
                	It doesn't store any sensitive details of the reports. Only the following datas are used:
                	id, program, title, reputation, bounties, state, creation date.
                </p>
                <b>Copyright</b>
                <p>
                	I don't believe in license, you can do want you want with this program.
                </p>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#about-btn').on('click', function(e) {
            e.preventDefault();
            $('#modalAbout').modal();
        });
    });
</script>
