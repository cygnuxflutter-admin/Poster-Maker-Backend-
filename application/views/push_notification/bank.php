<div class="container">
    <div class="page-header">
        <div class="wrap-title">
            <div class="icon"><span class="ico-folder"></span></div>
            <h1>Notification Bank <small><span class="cgreen">2,000 Unique Pre-Generated Drafts</span></small></h1>
        </div>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>site">Home</a></li>
            <li><a href="<?php echo base_url(); ?>PushNotification">Push Notifications</a></li>
            <li class="active">Content Bank</li>
        </ul>
        <div class="clear"></div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <strong>Success!</strong> <?php echo $this->session->flashdata('success'); ?>
        </div>
    <?php } ?>

    <!-- Navigation Tabs -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li><a href="<?php echo base_url('PushNotification'); ?>">📊 Dashboard</a></li>
                <li class="active"><a href="<?php echo base_url('PushNotification/bank'); ?>">🗂 Content Bank (2,000)</a></li>
                <li><a href="<?php echo base_url('PushNotification/composer'); ?>">✍️ Manual Push Composer</a></li>
                <li><a href="<?php echo base_url('PushNotification/campaigns'); ?>">📢 Campaign History</a></li>
                <li><a href="<?php echo base_url('PushNotification/test_send'); ?>">🚀 Send Test Push</a></li>
                <li><a href="<?php echo base_url('PushNotification/logs'); ?>">📜 Delivery Logs</a></li>
                <li><a href="<?php echo base_url('PushNotification/settings'); ?>">⚙️ Settings</a></li>
            </ul>
        </div>
    </div>

    <!-- Batch Approval Action Box -->
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-md-12">
            <div class="block" style="background: #fff; padding: 15px; border-radius: 4px;">
                <form action="<?php echo base_url('PushNotification/approve_batch'); ?>" method="POST" class="form-inline" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                    <strong><span class="ico-check"></span> Quick Batch Approval:</strong>
                    <span>Approve next</span>
                    <select name="count" class="form-control" style="width: auto;">
                        <option value="20">20 drafts (5 days)</option>
                        <option value="40" selected>40 drafts (10 days)</option>
                        <option value="100">100 drafts (25 days)</option>
                        <option value="280">Full category batch (~280)</option>
                    </select>
                    <span>from</span>
                    <select name="category_name" class="form-control" style="width: auto;">
                        <option value="">-- All Categories (Balanced) --</option>
                        <?php foreach ($categories as $c) { ?>
                            <option value="<?php echo htmlspecialchars($c['category_name']); ?>"><?php echo htmlspecialchars($c['category_name']); ?> (<?php echo $c['count']; ?>)</option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="btn btn-success">Approve Selected Batch</button>
                    <a href="<?php echo base_url('PushNotification/approve_all_drafts'); ?>" class="btn btn-primary" onclick="return confirm('Do you want to mark ALL remaining drafts as APPROVED for auto dispatch?')" style="margin-left: auto; font-weight: bold;">
                        ⚡ Approve All Remaining Drafts
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row" style="margin-bottom: 15px;">
        <div class="col-md-12">
            <form action="<?php echo base_url('PushNotification/bank'); ?>" method="GET" class="form-inline" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <label>Filter Status:</label>
                <select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="DRAFT" <?php echo ($filters['status'] === 'DRAFT') ? 'selected' : ''; ?>>DRAFT (Review)</option>
                    <option value="APPROVED" <?php echo ($filters['status'] === 'APPROVED') ? 'selected' : ''; ?>>APPROVED (Ready to Send)</option>
                    <option value="SENT" <?php echo ($filters['status'] === 'SENT') ? 'selected' : ''; ?>>SENT (Dispatched)</option>
                    <option value="FAILED" <?php echo ($filters['status'] === 'FAILED') ? 'selected' : ''; ?>>FAILED</option>
                </select>

                <label>Category:</label>
                <select name="category" class="form-control" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    <?php foreach ($categories as $c) { ?>
                        <option value="<?php echo htmlspecialchars($c['category_name']); ?>" <?php echo ($filters['category_name'] === $c['category_name']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['category_name']); ?>
                        </option>
                    <?php } ?>
                </select>

                <input type="text" name="search" class="form-control" placeholder="Search title or body..." value="<?php echo htmlspecialchars($filters['search']); ?>" style="width: 200px;">
                <button type="submit" class="btn btn-primary">Filter</button>
                <?php if (!empty($filters['status']) || !empty($filters['category_name']) || !empty($filters['search'])) { ?>
                    <a href="<?php echo base_url('PushNotification/bank'); ?>" class="btn btn-default">Reset</a>
                <?php } ?>
                <span class="pull-right text-muted" style="margin-left: auto;">
                    Showing <?php echo count($items); ?> of <?php echo number_format($total); ?> records
                </span>
            </form>
        </div>
    </div>

    <!-- Content Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="head blue">
                    <div class="icon"><span class="ico-list"></span></div>
                    <h2>Bank Items (Total: <?php echo number_format($total); ?>)</h2>
                </div>
                <div class="data-fluid">
                    <table class="table" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th style="width: 250px;">Title</th>
                                <th>Notification Body</th>
                                <th style="width: 180px;">Category</th>
                                <th style="width: 80px;">Slot</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;" class="TAC">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($items)) { foreach ($items as $item) { ?>
                            <tr id="row-<?php echo $item['id']; ?>">
                                <td>#<?php echo $item['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($item['title'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                                <td><?php echo htmlspecialchars($item['body'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <span class="label label-info"><?php echo htmlspecialchars($item['category_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                                    <br><small class="text-muted">cat_id: <?php echo $item['category_id']; ?></small>
                                </td>
                                <td><code><?php echo $item['scheduled_slot']; ?></code></td>
                                <td id="status-badge-<?php echo $item['id']; ?>">
                                    <?php if ($item['status'] === 'APPROVED') { ?>
                                        <span class="label label-success">APPROVED</span>
                                    <?php } elseif ($item['status'] === 'DRAFT') { ?>
                                        <span class="label label-default">DRAFT</span>
                                    <?php } elseif ($item['status'] === 'SENT') { ?>
                                        <span class="label label-primary">SENT</span>
                                    <?php } elseif ($item['status'] === 'FAILED') { ?>
                                        <span class="label label-danger">FAILED</span>
                                    <?php } else { ?>
                                        <span class="label label-warning"><?php echo $item['status']; ?></span>
                                    <?php } ?>
                                </td>
                                <td class="TAC">
                                    <?php if ($item['status'] === 'DRAFT') { ?>
                                        <button class="btn btn-xs btn-success" onclick="toggleStatus(<?php echo $item['id']; ?>, 'APPROVED')">
                                            ✓ Approve
                                        </button>
                                    <?php } elseif ($item['status'] === 'APPROVED') { ?>
                                        <button class="btn btn-xs btn-warning" onclick="toggleStatus(<?php echo $item['id']; ?>, 'DRAFT')">
                                            Revert
                                        </button>
                                    <?php } else { ?>
                                        <span class="text-muted"><small><?php echo $item['sent_at'] ? date('M d, H:i', strtotime($item['sent_at'])) : '-'; ?></small></span>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } } else { ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 30px;">
                                    No notification drafts match the current filter.
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pages > 1) { ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <ul class="pagination">
                <?php if ($page > 1) { ?>
                    <li><a href="<?php echo base_url('PushNotification/bank?' . http_build_query(array_merge($filters, array('page' => $page - 1)))); ?>">&laquo; Prev</a></li>
                <?php } ?>
                <li class="active"><span>Page <?php echo $page; ?> of <?php echo $pages; ?></span></li>
                <?php if ($page < $pages) { ?>
                    <li><a href="<?php echo base_url('PushNotification/bank?' . http_build_query(array_merge($filters, array('page' => $page + 1)))); ?>">Next &raquo;</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>

</div>

<script>
function toggleStatus(id, newStatus) {
    $.ajax({
        url: '<?php echo base_url("PushNotification/update_status"); ?>',
        type: 'POST',
        data: { id: id, status: newStatus },
        dataType: 'json',
        success: function(resp) {
            if (resp.success) {
                var badge = $('#status-badge-' + id);
                if (newStatus === 'APPROVED') {
                    badge.html('<span class="label label-success">APPROVED</span>');
                    $('#row-' + id + ' .TAC').html('<button class="btn btn-xs btn-warning" onclick="toggleStatus(' + id + ', \'DRAFT\')">Revert</button>');
                } else {
                    badge.html('<span class="label label-default">DRAFT</span>');
                    $('#row-' + id + ' .TAC').html('<button class="btn btn-xs btn-success" onclick="toggleStatus(' + id + ', \'APPROVED\')">✓ Approve</button>');
                }
            } else {
                alert('Failed to update status.');
            }
        },
        error: function() {
            alert('Server error occurred.');
        }
    });
}
</script>
