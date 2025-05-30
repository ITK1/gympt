// gym-effects.js

// Hiệu ứng nhịp tim cho tiêu đề hero
function heartbeatEffect() {
  const heroTitle = document.querySelector('.hero-content h1');
  if (!heroTitle) return;

  let scaleUp = true;
  setInterval(() => {
    if (scaleUp) {
      heroTitle.style.transform = 'scale(1.1)';
      heroTitle.style.transition = 'transform 0.3s ease-in-out';
    } else {
      heroTitle.style.transform = 'scale(1)';
      heroTitle.style.transition = 'transform 0.3s ease-in-out';
    }
    scaleUp = !scaleUp;
  }, 700);
}

// Tạo các icon tạ đung đưa trên nền
function createDumbbellParticles() {
  const container = document.createElement('div');
  container.classList.add('dumbbell-container');
  document.body.appendChild(container);

  const NUM_PARTICLES = 15;

  for (let i = 0; i < NUM_PARTICLES; i++) {
    const dumbbell = document.createElement('div');
    dumbbell.classList.add('dumbbell');
    dumbbell.style.left = Math.random() * 100 + 'vw';
    dumbbell.style.top = Math.random() * 100 + 'vh';
    dumbbell.style.animationDelay = (Math.random() * 5) + 's';
    dumbbell.style.animationDuration = 4 + Math.random() * 3 + 's';
    container.appendChild(dumbbell);
  }
}

// Khởi tạo hiệu ứng khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
  heartbeatEffect();
  createDumbbellParticles();
});
