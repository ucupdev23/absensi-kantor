<div class="container py-5" style="max-width:520px;">
  <div class="card shadow-sm">
    <div class="card-body p-4">
      <h4 class="mb-1" style="color:#670F7A;">Verifikasi OTP</h4>
      <p class="text-muted mb-3">Masukkan OTP yang dikirim ke WhatsApp.</p>

      <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
      <?php endif; ?>
      <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
      <?php endif; ?>

      <div class="mb-2">
        <label class="form-label">Username</label>
        <input class="form-control" value="<?= htmlspecialchars($username); ?>" readonly>
      </div>

      <div class="mb-3">
        <label class="form-label">No WhatsApp</label>
        <input class="form-control" value="<?= htmlspecialchars($no_wa); ?>" readonly>
      </div>

      <form method="post" action="<?= base_url('auth/forgot_password/verify'); ?>">
        <div class="mb-3">
          <label class="form-label">Kode OTP</label>
          <input type="text" name="kode_otp" class="form-control" placeholder="Masukkan kode OTP" required>
        </div>
        <button class="btn w-100 text-white" style="background:#670F7A;">Verifikasi</button>
      </form>

      <div class="d-flex justify-content-between align-items-center mt-3">
        <small class="text-muted">
          Kirim ulang dalam <b><span id="timer">60</span></b> detik
        </small>

        <form method="post" action="<?= base_url('auth/forgot_password/resend'); ?>">
          <button id="btnResend" class="btn btn-outline-secondary btn-sm" disabled>
            Kirim Ulang
          </button>
        </form>
      </div>

      <div class="text-center mt-3">
        <a href="<?= base_url('auth/login'); ?>" class="btn btn-sm btn-outline-primary">
          Kembali ke Login
        </a>
      </div>
    </div>
  </div>
</div>

<script>
(function(){
  const resendAt  = new Date("<?= date('c', strtotime($this->session->userdata('fp_resend_at'))); ?>").getTime();
  const timerEl  = document.getElementById('timer');
  const btn      = document.getElementById('btnResend');

  function tick(){
    const now = Date.now();
    let s = Math.ceil((resendAt - now) / 1000);

    if (s <= 0) {
      timerEl.textContent = '0';
      btn.disabled = false;
      return;
    }

    timerEl.textContent = s;
    btn.disabled = true;
    setTimeout(tick, 500);
  }

  tick();
})();
</script>
