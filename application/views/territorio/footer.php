	<!-- #footer -->

    </div>
    <!-- /#wrapper -->
    <?php
    $login = $this->uri->segment(2);
    if (!empty($login) && $login=="login") { $this->load->view("common/description.php"); }
    ?>

    <script src="<?=site_url('assets/js/bootstrap.min.js')?>"></script>
    <script src="<?=site_url('assets/js/plugins/metisMenu/metisMenu.min.js')?>"></script>
    <script src="<?=site_url('assets/js/sb-admin-2.js')?>"></script>
	
</body>
</html>
<!-- /#footer -->