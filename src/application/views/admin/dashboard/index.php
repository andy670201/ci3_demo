<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="bi bi-speedometer2 me-2 text-orange"></i>儀表板</h4>
    <small class="text-muted"><?= date('Y年m月d日') ?></small>
</div>

<!-- 統計卡片 -->
<div class="row g-3 mb-4">
    <?php
    $cards = [
        ['label'=>'商品總數',   'value'=>$stats['total_products'],  'icon'=>'box-seam',        'color'=>'#FF6B35'],
        ['label'=>'上架商品',   'value'=>$stats['active_products'], 'icon'=>'check-circle',    'color'=>'#28a745'],
        ['label'=>'訂單總數',   'value'=>$stats['total_orders'],    'icon'=>'bag-check',       'color'=>'#007bff'],
        ['label'=>'待處理訂單', 'value'=>$stats['pending_orders'],  'icon'=>'hourglass-split', 'color'=>'#ffc107'],
        ['label'=>'未讀訊息',   'value'=>$stats['unread_messages'], 'icon'=>'envelope-exclamation','color'=>'#dc3545'],
        ['label'=>'會員總數',   'value'=>$stats['total_members'],   'icon'=>'people',          'color'=>'#6f42c1'],
    ];
    foreach ($cards as $c): ?>
    <div class="col-6 col-md-4 col-xl-2">
        <div class="card border-0 shadow-sm h-100 text-center p-3" style="border-radius:12px;">
            <div class="fs-2 mb-1" style="color:<?= $c['color'] ?>"><i class="bi bi-<?= $c['icon'] ?>"></i></div>
            <div class="fw-bold fs-3"><?= $c['value'] ?></div>
            <div class="text-muted small"><?= $c['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">
    <!-- 最新訂單 -->
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-bag me-2 text-orange"></i>最新訂單</h6>
                <a href="<?= base_url('admin/orders') ?>" class="btn btn-sm btn-outline-secondary">查看全部</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>訂單編號</th><th>金額</th><th>狀態</th><th>時間</th></tr>
                        </thead>
                        <tbody>
                        <?php if ($recent_orders): foreach ($recent_orders as $o): ?>
                        <?php
                        $status_map = ['pending'=>['待確認','warning'],'confirmed'=>['已確認','primary'],'shipping'=>['配送中','info'],'delivered'=>['已送達','success'],'cancelled'=>['已取消','secondary']];
                        $s = $status_map[$o->status] ?? [$o->status,'secondary'];
                        ?>
                        <tr>
                            <td><a href="<?= base_url('admin/orders/'.$o->id) ?>" class="text-decoration-none fw-semibold" style="color:#FF6B35;"><?= $o->order_number ?></a></td>
                            <td>$<?= number_format($o->total_amount) ?></td>
                            <td><span class="badge bg-<?= $s[1] ?>"><?= $s[0] ?></span></td>
                            <td class="text-muted small"><?= date('m/d H:i', strtotime($o->created_at)) ?></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">尚無訂單</td></tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- 未讀訊息 -->
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center pt-3 pb-2">
                <h6 class="fw-bold mb-0"><i class="bi bi-envelope me-2 text-orange"></i>未讀訊息</h6>
                <a href="<?= base_url('admin/contacts') ?>" class="btn btn-sm btn-outline-secondary">查看全部</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                <?php if ($recent_messages): foreach ($recent_messages as $m): ?>
                <a href="<?= base_url('admin/contacts/'.$m->id) ?>" class="list-group-item list-group-item-action px-4 py-3">
                    <div class="d-flex justify-content-between">
                        <span class="fw-semibold"><?= htmlspecialchars($m->name) ?></span>
                        <small class="text-muted"><?= date('m/d', strtotime($m->created_at)) ?></small>
                    </div>
                    <div class="text-muted small text-truncate"><?= htmlspecialchars($m->subject) ?></div>
                </a>
                <?php endforeach; else: ?>
                <div class="text-center text-muted py-4">無未讀訊息</div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
