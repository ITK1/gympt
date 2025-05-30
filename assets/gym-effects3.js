
document.addEventListener("DOMContentLoaded", () => {
  // 🔥 Hiệu ứng lăn chuột vào phần huấn luyện viên
  const trainerCards = document.querySelectorAll('.trainer-card');
  trainerCards.forEach(card => {
    card.addEventListener('mouseenter', () => {
      card.style.transform = 'scale(1.05)';
      card.style.boxShadow = '0 4px 20px rgba(255,76,96,0.5)';
    });
    card.addEventListener('mouseleave', () => {
      card.style.transform = 'scale(1)';
      card.style.boxShadow = '0 2px 12px rgba(0,0,0,0.1)';
    });
  });

  // 💥 Hiệu ứng tiêu đề hero lướt sóng
  const heroText = document.querySelector('.hero-content h1');
  if (heroText) {
    let wave = 0;
    setInterval(() => {
      wave += 0.03;
      heroText.style.transform = `translateY(${Math.sin(wave) * 5}px)`;
    }, 30);
  }

  // 💡 Nút CTA rung nhẹ khi hover
  const ctaButton = document.querySelector('.cta-button');
  if (ctaButton) {
    ctaButton.addEventListener('mouseover', () => {
      ctaButton.style.animation = 'pulse 0.6s infinite';
    });
    ctaButton.addEventListener('mouseout', () => {
      ctaButton.style.animation = 'none';
    });
  }
});

// 🌟 Animation pulse
const style = document.createElement('style');
style.innerHTML = `
@keyframes pulse {
  0% { transform: scale(1); }
  50% { transform: scale(1.06); }
  100% { transform: scale(1); }
}`;
document.head.appendChild(style);
