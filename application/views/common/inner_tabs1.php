<script type="text/javascript">
    <!--
    function MM_swapImgRestore() { //v3.0
        var i, x, a = document.MM_sr;
        for (i = 0; a && i < a.length && (x = a[i]) && x.oSrc; i++)
            x.src = x.oSrc;
    }

    function MM_preloadImages() { //v3.0
        var d = document;
        if (d.images) {
            if (!d.MM_p)
                d.MM_p = new Array();
            var i, j = d.MM_p.length,
                a = MM_preloadImages.arguments;
            for (i = 0; i < a.length; i++)
                if (a[i].indexOf("#") != 0) {
                    d.MM_p[j] = new Image;
                    d.MM_p[j++].src = a[i];
                }
        }
    }

    function MM_findObj(n, d) { //v4.01
        var p, i, x;
        if (!d)
            d = document;
        if ((p = n.indexOf("?")) > 0 && parent.frames.length) {
            d = parent.frames[n.substring(p + 1)].document;
            n = n.substring(0, p);
        }
        if (!(x = d[n]) && d.all)
            x = d.all[n];
        for (i = 0; !x && i < d.forms.length; i++)
            x = d.forms[i][n];
        for (i = 0; !x && d.layers && i < d.layers.length; i++)
            x = MM_findObj(n, d.layers[i].document);
        if (!x && d.getElementById)
            x = d.getElementById(n);
        return x;
    }

    function MM_swapImage() { //v3.0
        var i, j = 0,
            x, a = MM_swapImage.arguments;
        document.MM_sr = new Array;
        for (i = 0; i < (a.length - 2); i += 3)
            if ((x = MM_findObj(a[i])) != null) {
                document.MM_sr[j++] = x;
                if (!x.oSrc)
                    x.oSrc = x.src;
                x.src = a[i + 2];
            }
    }
    //
    -->
</script>

<body onload="MM_preloadImages('../icons2/acc2.png', '../icons2/clients2.png', '../icons2/marketing2.png', '../../icons2/user2.png')">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td width="14%"><span style="font-size: 14px; color: #666; font-weight: bold;">Latest offer:

                </span> </td>
            <td width="86%">
                <marquee scrollamount="2" scrolldelay="1" style="font-size: 12px; color: #F00;"><?php
                                                                                                ?>
                </marquee>
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>

            <?php
            $user_type = $this->session->userdata('user_type');
            if ($user_type == 'cash_user' || $user_type == 'admin'):
            ?>
                <td>
                    <div align="center">
                        <a href="<?php echo site_url('TestController/add_test') ?>" onmouseout="">
                            <img src="<?php echo base_url() ?>/icons2/dealer.png" name="Image1" width="175" height="175" border="0" id="Image1" /></a>
                    </div>
                </td>
            <?php
            endif;
            ?>


            <?php
            $user_type = $this->session->userdata('user_type');
            if ($user_type == 'lab_user' || $user_type == 'admin'):
            ?>
                <td>
                    <div align="center"><a href="<?php echo site_url('TestResultController/add_test_result') ?>"><img src="<?php echo base_url() ?>/icons2/retailer.png" name="Image2" width="175" height="175" border="0" id="Image2" /></a></div>
                </td>
            <?php
            endif;
            ?>
            <?php
            $user_type = $this->session->userdata('user_type');
            if ($user_type == 'cash_user' || $user_type == 'admin'):
            ?>
                <td>
                    <div align="center"><a href="<?php echo site_url('DoctorController/add_doctor') ?>"><img src="<?php echo base_url() ?>/icons2/purchase.png" name="Image3" width="175" height="175" border="0" id="Image3" /></a></div>
                </td>
            <?php
            endif;
            ?>


        </tr>
        <tr>

            <?php
            $user_type = $this->session->userdata('user_type');
            if ($user_type == 'admin'):
            ?>
                <td>
                    <div align="center"><a href="<?php echo site_url('HomeController/user') ?>"><img src="<?php echo base_url() ?>/icons2/user.png" name="Image5" width="175" height="175" border="0" id="Image5" /></a></div>
                </td>

            <?php
            endif;
            ?>



        </tr>
    </table>
    <?php
    //        echo '<pre>';
    //        $user_type=$this->session->userdata('user_type');
    //        var_dump($user_type);
    //        die;
    ?>
    <!-- /tabs -->

    <!-- Tab01 -->