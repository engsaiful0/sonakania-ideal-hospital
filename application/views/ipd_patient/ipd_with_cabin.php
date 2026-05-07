 <link rel="stylesheet" media="screen,projection" type="text/css" href="<?php echo base_url() ?>css/bootstrap_4.6.2.min.css" />
<div class="panel panel-primary">
    <div class="panel-heading text-center mb-3">
        <h3>IPD with Cabin</h3>
    </div>
    <!-- Tabs -->
    <div class="panel-body">
        <?php
        $cabins = $this->db->select('*')->get('cabin')->result();
        foreach ($cabins as $cabin) {
            $patient = $this->db->where('ipd_patient_id', $cabin->ipd_patient_id)->get('ipd_patient')->row();
            $cardClass = ($cabin->status == 'Not Available') ? 'bg-success text-white' : 'bg-light';
        ?>
            <div class="col-md-2 mb-3">
                <div style="height: 170px;" class="card <?= $cardClass ?> shadow border-0">
                    <div class="card-header font-weight-bold">
                        <span class="badge badge-pill badge-info p-2" style="font-size: 1.2rem;">
                            🏨 Cabin #: <?= htmlspecialchars($cabin->cabin_number) ?>
                        </span>
                    </div>
                    <div class="card-body">
                        <p>Status: <strong><?= htmlspecialchars($cabin->status) ?></strong></p>
                        <p>Rent: <?= htmlspecialchars($cabin->cabin_rent) ?>৳</p>
                        <?php if ($cabin->status == 'Not Available' && $patient): ?>
                            <div class="border rounded p-2 mt-2 bg-white text-dark">
                                <h5 class="mb-1">👤 <?= htmlspecialchars($patient->patient_name) ?></h5>
                                <p class="mb-0">📞 <?= htmlspecialchars($patient->mobile_number) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>