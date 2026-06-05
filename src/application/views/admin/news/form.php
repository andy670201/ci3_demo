<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="bi bi-<?= $news ? 'pencil-square' : 'plus-circle' ?> me-2 text-orange"></i>
        <?= $news ? '編輯文章' : '新增文章' ?>
    </h4>
    <a href="<?= base_url('admin/news') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i>返回列表
    </a>
</div>

<?php $action = $news ? base_url('admin/news/update/'.$news->id) : base_url('admin/news/store'); ?>
<?= form_open($action) ?>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">標題 <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= $news ? htmlspecialchars($news->title) : '' ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">摘要</label>
                    <textarea name="summary" class="form-control" rows="3"><?= $news ? htmlspecialchars($news->summary) : '' ?></textarea>
                    <div class="form-text">顯示在列表頁的簡短說明</div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">內文</label>
                    <textarea name="content" class="form-control" rows="12"><?= $news ? $news->content : '' ?></textarea>
                    <div class="form-text">支援 HTML 標籤</div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius:12px;">
            <div class="card-header bg-white border-0 pt-3"><h6 class="fw-bold mb-0">發布設定</h6></div>
            <div class="card-body">
                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" name="is_published" id="isPublished" value="1"
                        <?= ($news && $news->is_published) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-semibold" for="isPublished">立即發布</label>
                    <div class="form-text">取消勾選則儲存為草稿</div>
                </div>
                <button type="submit" class="btn btn-orange text-white w-100 py-2 fw-semibold">
                    <i class="bi bi-check-lg me-2"></i><?= $news ? '儲存變更' : '建立文章' ?>
                </button>
                <a href="<?= base_url('admin/news') ?>" class="btn btn-outline-secondary w-100 mt-2">取消</a>
            </div>
        </div>
    </div>
</div>

<?= form_close() ?>
<style>.btn-orange{background:#FF6B35;border:none;}.btn-orange:hover{background:#e55a25;}</style>
